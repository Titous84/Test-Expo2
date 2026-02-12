<?php

namespace App\Repositories;

use PDOException;

/**
 * Class EvaluationGridRepository
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 * @package App\Repositories
 */
class EvaluationGridRepository extends Repository
{
    /**
     * Requête pour obtenir la liste de toutes les grilles d'évaluation
     * @return array|null
     */
    public function getEvaluationGrid()
    {
        try {
            $sql = "SELECT survey.id, survey.name as name FROM survey
            ORDER BY survey.name ASC";
            $req = $this->db->prepare($sql);
            $req->execute();
            $result = $req->fetchAll();
            return $result;
        } catch (PDOException $e) {
            $this->logHandler->error($e->getMessage());
            return null;
        }
    }

    /**
     * @author Jean-Christophe Demers
     * @author Thomas-Gabriel Paquin
     * Requête pour obtenir une grille d'évaluation et toutes ses sections et ses critères
     * @param $id
     * @return array|null
     */
    public function getEvaluationGridById($id)
    {
        try {
            $sql = "SELECT survey.id, survey.name as name, rating_section.id as rating_section_id, 
            rating_section.name as rating_section_name, rating_section.position as rating_section_position,
            criteria.id as criteria_id, criteria.criteria as criteria_name, criteria.position as criteria_position,
            criteria.max_value as criteria_max_value, criteria.incremental_value as criteria_incremental_value
            FROM survey
            LEFT JOIN rating_section ON rating_section.survey_id = survey.id
            LEFT JOIN criteria ON criteria.rating_section_id = rating_section.id
            WHERE survey.id = :id
            ORDER BY rating_section.position ASC, criteria.position ASC";
            $req = $this->db->prepare($sql);
            $req->bindParam(':id', $id);
            $req->execute();
            $result = [];
            while ($row = $req->fetch()) {
                $result["id"] = $row["id"];
                $result["name"] = $row["name"];
                if (isset($row["rating_section_id"])) {
                    $result["sections"][$row["rating_section_id"]]["name"] = $row["rating_section_name"];
                    $result["sections"][$row["rating_section_id"]]["position"] = $row["rating_section_position"];
                    if (isset($row["criteria_id"])) {
                        $result["sections"][$row["rating_section_id"]]["criterias"][$row["criteria_id"]]["name"] = $row["criteria_name"];
                        $result["sections"][$row["rating_section_id"]]["criterias"][$row["criteria_id"]]["position"] = $row["criteria_position"];
                        $result["sections"][$row["rating_section_id"]]["criterias"][$row["criteria_id"]]["max_value"] = $row["criteria_max_value"];
                        $result["sections"][$row["rating_section_id"]]["criterias"][$row["criteria_id"]]["incremental_value"] = $row["criteria_incremental_value"];
                    }else{
                        $result["sections"][$row["rating_section_id"]]["criterias"] = [];
                    
                    }
                }else{
                    $result["sections"] = [];
                }
            }
            if (isset($result["sections"])) {
                $result["sections"] = array_values($result["sections"]);
                foreach ($result["sections"] as &$section) {
                    if (isset($section["criterias"])) {
                        $section["criterias"] = array_values($section["criterias"]);
                    } else {
                        $section["criterias"] = [];
                    }
                }
            } else {
                $result["sections"] = [];
            }
            if (!isset($result["id"])) {
                $result = [];
            }

            return $result;
        } catch (PDOException $e) {
            $this->logHandler->error($e->getMessage());
            return null;
        }
    }

    /**
     * Requête pour supprimer une grille d'évaluation et toutes ses sections et tous ses critères
     * @param $id
     * @return bool
     */
    public function deleteEvaluationGridById($id)
    {
        try {
            $sql = "DELETE FROM criteria WHERE rating_section_id IN (SELECT id FROM rating_section WHERE survey_id = :id)";
            $req = $this->db->prepare($sql);
            $req->bindParam(':id', $id);
            $req->execute();

            $sql = "DELETE FROM rating_section WHERE survey_id = :id";
            $req = $this->db->prepare($sql);
            $req->bindParam(':id', $id);
            $req->execute();

            $sql = "DELETE FROM survey WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->bindParam(':id', $id);
            $req->execute();
            return true;
        } catch (PDOException $e) {
            $this->logHandler->error($e->getMessage());
            return false;
        }
    }

    /**
     * Requête pour ajouter une grille d'évaluation et toutes ses sections et tous ses critères
     * @param $id
     * @return int|null
     */
    public function insertEvaluationGrid($data){
        try {
            $sql = "INSERT INTO survey (name) VALUES (:name)";
            $req = $this->db->prepare($sql);
            $req->bindParam(':name', $data['name']);
            $req->execute();
            $surveyId = $this->db->lastInsertId();

            foreach ($data['rating_section'] as $rating_section) {
                $sql = 'INSERT INTO rating_section (name, position, survey_id) VALUES (:name, :position, :survey_id)';
                $req = $this->db->prepare($sql);
                $req->bindParam(":name", $rating_section["name"]);
                $req->bindParam(":position", $rating_section["position"]);
                $req->bindParam(":survey_id", $surveyId);
                $req->execute();
                $rating_section_id = $this->db->lastInsertId();

                foreach ($rating_section['criterias'] as $criteria) {
                    $sql = 'INSERT INTO criteria (criteria, position, max_value, incremental_value, rating_section_id) 
                    VALUES (:criteria, :position, :max_value, :incremental_value, :rating_section_id)';
                    $req = $this->db->prepare($sql);
                    $req->bindParam(':criteria', $criteria['name']);
                    $req->bindParam(':position', $criteria['position']);
                    $req->bindParam(':max_value', $criteria['max_value']);
                    $req->bindParam(':incremental_value', $criteria['incremental_value']);
                    $req->bindParam(':rating_section_id', $rating_section_id);
                    $req->execute();
                }
            }
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logHandler->error($e->getMessage());
            return null;
        }
    }

    /**
     * Requête pour mette à jour une grille d'évaluation et toutes ses sections ainsi que tous ses critères
     * @param $id
     * @return bool
     */
    public function updateEvaluationGrid($data)
    {
        try {
                $sql = 'UPDATE survey SET name = :name WHERE id = :id';
                $req = $this->db->prepare($sql);
                $req->bindParam(':name', $data['name']);
                $req->bindParam(':id', $data['id']);
                $req->execute();

                $sql = "DELETE FROM criteria WHERE rating_section_id IN (SELECT id FROM rating_section WHERE survey_id = :id)";
                $req = $this->db->prepare($sql);
                $req->bindParam(':id', $data['id']);
                $req->execute();

                $sql = "DELETE FROM rating_section WHERE survey_id = :id";
                $req = $this->db->prepare($sql);
                $req->bindParam(':id', $data['id']);
                $req->execute();

            foreach ($data['rating_section'] as $rating_section) {
                $sql = "INSERT INTO rating_section (name, position, survey_id) VALUES (:name, :position, :survey_id)";
                $req = $this->db->prepare($sql);
                $req->bindParam(':name', $rating_section['name']);
                $req->bindParam(':position', $rating_section['position']);
                $req->bindParam(':survey_id', $data['id']);
                $req->execute();
                $rating_section_id = $this->db->lastInsertId();

                foreach ($rating_section['criterias'] as $criteria) {
                    $sql = 'INSERT INTO criteria (criteria, position, max_value, incremental_value, rating_section_id) 
                    VALUES (:criteria, :position, :max_value, :incremental_value, :rating_section_id)';
                    $req = $this->db->prepare($sql);
                    $req->bindParam(':criteria', $criteria['name']);
                    $req->bindParam(':position', $criteria['position']);
                    $req->bindParam(':max_value', $criteria['max_value']);
                    $req->bindParam(':incremental_value', $criteria['incremental_value']);
                    $req->bindParam(':rating_section_id', $rating_section_id);
                    $req->execute();
                }
            }
            return true;
        } catch (PDOException $e) {
            $this->logHandler->error($e->getMessage());
            return false;
        }
    }
}