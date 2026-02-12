<?php

namespace App\Repositories;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Models\User;
use App\Repositories\Repository;
use PDOException;
use phpDocumentor\Compiler\Pass\Debug;
use phpDocumentor\GraphViz\Exception;
use function DI\env;

/**
 * Classe FormRepository
 * @author Tomy Chouinard
 * @package App\Repositories
 */
class FormRepository extends Repository
{


    /*********************************************************** QUESTIONS CRUD ********************************************************************************/
    /**
     * Fonction qui permet d'obtenir tous les critères d'une section donnée
     * @return array Retourne un tableau des critères, sinon retourne false.
     * @throws PDOException Peut lancer des erreurs PDOException.
     */
    public function get_all_criteria($id)
    {
        try {
            $sql = "SELECT id, rating_section_id, criteria, max_value, incremental_value
				FROM criteria WHERE rating_section_id = :id ORDER BY criteria";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "id" => $id
            ));
            return $req->fetchAll();
        }
        catch (Exception $exception){
            return array("Aucun critère n'a été retourné!.");
        }

    }

    /**
     * Fonction permettant de créer une nouvelle section.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result.
     */
    public function create_question(string $name,int $position,int $rating_section_id, int $maxValue,int $increment):Result
    {
        try{
            $sql = "INSERT INTO criteria(criteria,position,rating_section_id,max_value,incremental_value) VALUES (:name,:position,:rating_section_id,:maxValue,:increment);";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name,
                "position" => $position,
                "rating_section_id" => $rating_section_id,
                "maxValue" => $maxValue,
                "increment" => $increment
            ));

            return new Result(EnumHttpCode::CREATED, array("L'ajout du critère a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("L'ajout du critère a échoué."));
        }
    }

    /**
     * Fonction permettant de supprimer une question d'une section.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return bool|null Retourne la section, sinon retourne null.
     */
    public function deleteQuestion(int $id):Result
    {
        try{
            $sql = "DELETE from criteria WHERE id = :id;";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "id" => $id
            ));

            return new Result(EnumHttpCode::CREATED, array("La suppression du critère a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La suppression du critère a échoué."));
        }
    }

    /**
     * Fonction permettant de modifier une section.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result retourne un resultat avec un code http.
     */
    public function update_question(int $id, string $name,int $position, int $maxValue,int $increment):Result
    {
        try{
            $sql = "UPDATE criteria SET criteria = :name,criteria.position=:position ,max_value=:maxValue,incremental_value=:increment WHERE (id = :id);";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name,
                "position" => $position,
                "maxValue" => $maxValue,
                "increment" => $increment,
                "id" => $id
            ));

            return new Result(EnumHttpCode::CREATED, array("La modification de la section a réussie."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La modification de la section a échouée."));
        }
    }

    /*********************************************************** SECTIONS CRUD ********************************************************************************/
    /**
     * Fonction qui permet d'obtenir toutes les sections d'un formulaire donné
     * @author Tomy Chouinard
     * @return array|false Retourne un tableau des sections, sinon retourne false.
     * @throws PDOException Peut lancer des erreurs PDOException.
     */
    public function get_all_sections($id): array
    {
        try{
        $sql = "SELECT id, name, position, survey_id
				FROM rating_section WHERE survey_id = :id ORDER BY position ASC";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "id" => $id
        ));
        return $req->fetchAll();}
        catch (Exception $exception){
            return array($exception);
        }
    }

    /**
     * Fonction permettant de créer une nouvelle section.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result.
     */
    public function create_section(string $name,int $position, int $survey_id):Result
    {
        try{
            $sql = "INSERT INTO rating_section(name,position,survey_id) VALUES (:name,:position,:survey_id);";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name,
                "position" => $position,
                "survey_id" => $survey_id
            ));

            return new Result(EnumHttpCode::CREATED, array("L'ajout du formulaire a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("L'ajout du formulaire a échoué."));
        }
    }

    /**
     * Fonction permettant de supprimer une section d'un formulaire.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return bool|null Retourne la section, sinon retourne null.
     */
    public function deleteSection(int $id):Result
    {
        try{
            $sql = "DELETE from rating_section WHERE id = :id;";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "id" => $id
            ));

            return new Result(EnumHttpCode::CREATED, array("La suppression de la section a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La suppression de la section a échoué."));
        }
    }

    /**
     * Fonction permettant de modifier une section.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result retourne un resultat avec un code http.
     */
    public function update_section(int $id, string $name,int $position, int $survey_id):Result
    {
        try{
            $sql = "UPDATE rating_section SET rating_section.name = :name,rating_section.position=:position ,survey_id=:survey_id WHERE (id = :id);";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name,
                "position" => $position,
                "survey_id" => $survey_id,
                "id" => $id
            ));

            return new Result(EnumHttpCode::CREATED, array("La modification de la section a réussie."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La modification de la section a échouée."));
        }
    }

    /*********************************************************** SURVEY CRUD ********************************************************************************/
    /**
     * Fonction qui permet d'obtenir toutes les formulaires
     * @author Tomy Chouinard
     * @return array|false Retourne un tableau des formulaires, sinon retourne false.
     * @throws PDOException Peut lancer des erreurs PDOException.
     */
    public function get_all_survey(): array{
        try{
        $sql = "SELECT id, name FROM survey ORDER BY id";
        $req = $this->db->query($sql);
        return $req->fetchAll();}
        catch(Exception $exception){
            return array($exception);
        }
    }

    /**
     * Fonction permettant de créer un nouveau formulaire.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result.
     */
    public function create_survey(string $name):Result
    {
        try{
            $sql = "INSERT INTO survey (name) VALUES (:name);";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name
            ));

            return new Result(EnumHttpCode::CREATED, array("L'ajout du formulaire a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("L'ajout du formulaire a échoué."));
        }
    }

    /**
     * Fonction permettant de supprimer un formulaire.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result Retourne un resultat avec code http.
     */
    public function deleteSurvey(int $id):Result
    {
        try{
            // Supprime tous les utilisations du questionnaire
            $sql = "DELETE from evaluation WHERE survey_id = :id;";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "id" => $id
            ));

            $sql = "DELETE from survey WHERE id = :id;";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "id" => $id
            ));

            return new Result(EnumHttpCode::CREATED, array("La suppression du formulaire a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La suppression du formulaire a échoué."));
        }
    }

    /**
     * Fonction permettant de modifier un formulaire.
     * @author Tomy Chouinard
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return Result retourne un resultat avec un code http.
     */
    public function update_survey(int $id, string $name, int $survey_id):Result
    {
        try{
            $sql = "UPDATE survey SET survey.name = :name WHERE id = :id;";
            $req = $this->db->prepare($sql);

            $req->execute(array(
                "name" => $name,
                "id" => $id,
            ));

            return new Result(EnumHttpCode::CREATED, array("La modification du formulaire a réussi."));
        }
        catch (Exception $exception){
            return new Result(EnumHttpCode::BAD_REQUEST, array("La modification du formulaire a échoué."));
        }
    }

}
?>
