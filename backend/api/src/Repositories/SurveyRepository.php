<?php

namespace App\Repositories;

use App\Models\SurveyCommentResult;
use App\Models\SurveyQuestionResult;
use PDO;
use PDOException;

/**
 * Classe SurveyRepository.
 * @package App\Repositories
 * @author Christopher Boisvert
 */
class SurveyRepository extends Repository
{    
    /**
	 * @author Christopher Boisvert
	 * @author Jean-Christophe Demers
     * Fonction qui permet d'obtenir les formulaires d'évaluations par l'uuid du juge.
     * @param string $judge_uuid UUID du juge.
     * @return array Retourne un tableau contenant les formulaires d'évaluations, sinon retourne un tableau vide.
     */
    public function get_all_survey_by_judge_id(string $judgeUUID): array
    {
        $sql = "SELECT evaluation.id, teams.name as stand_name, teams.id as stand_id, evaluation.survey_id, time_slots.time AS evaluation_start, evaluation.comments
        FROM evaluation
        INNER JOIN teams ON evaluation.teams_id=teams.id 
        INNER JOIN judge ON evaluation.judge_id=judge.id
        INNER JOIN time_slots ON evaluation.heure=time_slots.id
        WHERE judge.uuid=:uuid AND evaluation.est_actif=1
        ORDER BY teams.name AND evaluation.heure;";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "uuid" => $judgeUUID
        ));
        //if request is empty
        if($req->rowCount() == 0){
            echo "empty list";
            return array();
        }

        return $req->fetchAll();
    }

    /**
     * Fonction qui permet d'obtenir les évaluations et leurs informations
     * @return array Retourne un tableau contenant les formulaires d'évaluations, sinon retourne un tableau vide.
     */
    public function get_all_evaluation(): array
    {
        try{
            $sql = "SELECT evaluation.id, judge_id, teams.team_number as stand_id, evaluation.survey_id, heure as hour FROM evaluation INNER JOIN teams ON evaluation.teams_id = teams.id; ";
            $req = $this->db->query($sql);
            //if request is empty
            if($req->rowCount() == 0){
                echo "empty list";
                return array();
            }
            return $req->fetchAll();
        }
        catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return array();
        }

    }

    /**
     * Fonction qui permet d'obtenir les sections par l'ID d'un formulaire d'évaluation.
     * @param int $survey_id Identifiant du formulaire d'évaluation.
     * @return array Retourne un tableau des sections d'un formulaire, sinon retourne un tableau vide.
     */
    public function get_all_sections_by_survey_id(int $surveyId): array
    {
        $sql = "SELECT id, name, position FROM rating_section WHERE survey_id=:survey_id ORDER BY position ASC;";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "survey_id" => $surveyId
        ));

        return $req->fetchAll();
    }

    /**
     * Fonction qui permet d'obtenir les questions par l'ID d'une section.
     * @param int $section_id Identifiant d'une section.
     * @return array Retourne un tableau des questions d'une section, sinon retourne un tableau vide.
     */
    public function get_all_questions_by_section_id_and_evaluation_id(int $sectionId): array
    {
        $sql = "SELECT criteria.id, criteria.position, criteria, max_value AS 'maxValue', incremental_value AS 'incrementalValue'
        FROM criteria 
        WHERE criteria.rating_section_id=:rating_section_id
        ORDER BY position ASC";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "rating_section_id" => $sectionId
        ));

        return $req->fetchAll();
    }

    /**
     * Fonction qui permet d'obtenir le score d'une question par l'identifiant d'évaluation et de la question concerné.
     * @param SurveyQuestionResult Prend un objet de type SurveyQuestionResult.
     * @return int|null Retourne le score de la question si elle est trouvé, et null dans le cas contraire.
     */
    public function get_question_result_by_evaluation_id_and_criteria_id(SurveyQuestionResult $surveyQuestionResult)
    {
        $sql = "SELECT score FROM criteria_evaluation WHERE evaluation_id=:evaluation_id && criteria_id=:criteria_id";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "evaluation_id" => $surveyQuestionResult->evaluation_id,
            "criteria_id" => $surveyQuestionResult->criteria_id
        ));

        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result == false ? null : $result["score"];
    }

    /**
	 * @author Jean-Christophe Demers
     * Fonction qui remplace dans la bd le commentaire du juge.
     * @param SurveyCommentResult Prend un objet de type SurveyQuestionResult.
     * @return int Retourne le nombre de champ ajouté.
     */
    public function set_comment_result(SurveyCommentResult $surveyCommentResult): int
    {
        $sql = "UPDATE evaluation SET comments = :comment WHERE id = :id;";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "comment" => $surveyCommentResult->comment,
            "id" => $surveyCommentResult->evaluation_id,
        ));

        return $req->rowCount();
    }

    /**
     * Fonction qui ajoute ou remplace dans la bd la réponse du juge.
     * @param SurveyQuestionResult Prend un objet de type SurveyQuestionResult.
     * @return int Retourne le nombre de champ ajouté.
     */
    public function add_or_replace_question_result(SurveyQuestionResult $surveyQuestionResult): int
    {
        $sql = "REPLACE INTO criteria_evaluation(score, evaluation_id, criteria_id) VALUES(:score, :evaluation_id, :criteria_id);";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "score" => $surveyQuestionResult->score,
            "evaluation_id" => $surveyQuestionResult->evaluation_id,
            "criteria_id" => $surveyQuestionResult->criteria_id
        ));

        return $req->rowCount();
    }

    /**
     * Fontion qui permet d'obtenir le score d'un formulaire
     * @param int $evaluationId Id d'une évaluation.
     * @return int|null Le score de l'évaluation si trouvé, sinon false.
     */
    public function get_survey_score( int $evaluationId )
    {
        $sql = "SELECT SUM(score) AS score FROM criteria_evaluation WHERE evaluation_id=:evaluation_id";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "evaluation_id" => $evaluationId
        ));

        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result == false ? null : $result["score"];
    }

    /**
     * Fonction qui permet de fermer définitivement un formulaire d'évaluation.
     * @param int $evaluationId Id d'une évaluation.
     * @return int Retourne le nombre de ligne modifié.
     */
    public function close_survey( int $evaluationId )
    {
        $sql = "UPDATE evaluation SET est_actif=0 WHERE id=:evaluation_id";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "evaluation_id" => $evaluationId
        ));

        return $req->rowCount();
    }

    /**
     * Fonction qui permet d'obtenir tous les juges qui sont pas blacklisté.
     * @return array Retourne les juges trouvés, sinon un tableau vide.
     */
    public function find_all_judge_not_blacklisted()
    {
        $sql = "SELECT judge.id, users.first_name, users.last_name, users.email
        FROM judge
        INNER JOIN users
        ON judge.users_id=users.id
        WHERE users.blacklisted IS NOT true AND users.activated IS true AND role_id=1;";
        $req = $this->db->query($sql);
        return $req->fetchAll();
    }

    /**
     * Fonction qui le code UUID d'un juge.
     * @return int Retourne le nombre de ligne modifié.
     */
    public function change_uuid_judge(int $judge_id, string $uuid)
    {
        $sql = "UPDATE judge SET uuid=:uuid WHERE id=:judge_id";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "uuid" => $uuid,
            "judge_id" => $judge_id
        ));

        return $req->rowCount();
    }

    /**
     * Fonction qui le code UUID d'un juge individuellement.
     * @return int Retourne le nombre de ligne modifié.
     */
    public function change_uuid_judgeIndividually(int $judge_id, string $uuid)
    {
        $sql = "UPDATE judge SET uuid=:uuid WHERE users_id=:judge_id";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            "uuid" => $uuid,
            "judge_id" => $judge_id
        ));

        return $req->rowCount();
    }
}
