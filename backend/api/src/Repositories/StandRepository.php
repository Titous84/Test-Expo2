<?php

namespace App\Repositories;

use PDOException;

/**
 * Classe StandRepository
 * @package App\Repositories
 * @author Alex Des Ruisseaux
 */
class StandRepository extends Repository
{
	/**
	 * Fonction qui permet d'ajouter un evaluation
	 * @param $time heure de l'evaluation
     * @param $stand Numero du stand evaluer
     * @param @judgeId id du juge
	 * @param @surveyId id du survey
	 * @return int Retourne le nombre de lignes ajouté.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function add_time_stand( $time,$stand,$judgeId,$surveyId ): int
    {
		try{
	    $sql = "INSERT INTO evaluation(judge_id,teams_id,global_score,comments,survey_id,heure,est_actif) 
			    VALUES(:judgeId,(SELECT id FROM teams 
				WHERE team_number=:stand),0,'',:surveyId,:heure,1);";
	    $req = $this->db->prepare($sql);
	    $req->execute(array(
		    "heure" => $time,
		    "stand" => $stand,
			"judgeId" => $judgeId,
			"surveyId" => $surveyId
	    ));
		if($req->rowCount() > 0){
			$sql2 = "UPDATE `teams` SET `judge_assignation`= 1 WHERE team_number = :stand;";
			$req2 = $this->db->prepare($sql2);
			$req2->execute(array(
				"stand" => $stand,
			));
		}
	    return $req->rowCount();
		}catch(PDOException $e){
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return array();
		}
    }

    /**
	 * Fonction qui permet de verifier un stand
     * @param $stand Numero du stand evaluer
     * @param @judgeFirstName prenom du juge
     * @param @judgeLastName nom du juge
	 * @return int Retourne le nombre de lignes ajouté.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function conflict_stand( $stand,$judgeName ): int
    {
		try{
	    $sql = "SELECT id 
				FROM acquaintance_conflict 
				WHERE judge_id=(SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE CONCAT(first_name, ' ', last_name)=:judgeName LIMIT 1)) 
				AND users_id IN (SELECT users_id FROM users_teams WHERE teams_id=(SELECT id FROM teams WHERE team_number=:stand))";
	    $req = $this->db->prepare($sql);
	    $req->execute(array(
            "judgeName" => $judgeName,
            "stand" => $stand,
	    ));
	    return $req->rowCount();
	}catch(PDOException $e){
		$context["http_error_code"] = $e->getCode();
		$this->logHandler->critical($e->getMessage(), $context);
		return array();
	}
    }
}