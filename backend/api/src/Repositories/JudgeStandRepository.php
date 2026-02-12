<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Handlers\LogHandler;
/**
 * Repository: script pour obtenir les juges et les informations relié aux évaluations.
 * Souleymane Soumaré
 * Déreck "The GOAT" Lachance
 */
class JudgeStandRepository extends Repository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
    * @var logHandler
    */
    protected $logHandler;

    /**
     * Constructeur.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection, LogHandler $logHandler)
    {
        $this->connection = $connection;
        $this->logHandler = $logHandler;
    }
    
    /** 
     * @author Xavier Houle (Auteur principal inconnu)
     * Fonction pour selectionner l'ensemble des juges.
     * @return array un tableau  des juges
    */
    public function selectJudge() : array
    {

        $sql = "SELECT judge.id, categories_id, CONCAT(users.first_name, ' ', users.last_name) AS nom_complet, categories.survey_id from judge 
        INNER JOIN users ON users.id = judge.users_id INNER JOIN categories ON categories.id = categories_id
        WHERE users.blacklisted = 0;";

        $query = $this->connection->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }


    /** 
     * @author Xavier Houle
     * Fonction pour ajouter une évaluation.
     * @param array l'ensemble des données fournie par le côté client. 
     * (information nécessaire sur l'évaluation pour l'insertion)
     * @return int un tableau  des juges
    */
    public function add_evaluation(array $data) : int
    {
        $teams_id = $this->find_teams_id($data['stand_id']);

        $sql = "INSERT INTO `evaluation`(`judge_id`, `teams_id`, `comments`, `survey_id`, `heure`, `est_actif`) VALUES (:judge_id, :teams_id, ' ', :surveyId,:heure, 1);";

        $query = $this->connection->prepare($sql);
        $query->execute(array(
            ":judge_id" => $data['judge_id'],
            ":teams_id" => $teams_id['id'],
            ":surveyId" => $data['survey_id'],
            ":heure" => $data['heure']
        ));

        return $this->connection->lastInsertId();
    }

    /** 
     * @author Xavier Houle
     * Fonction pour modifier une évaluation.
     * @param array l'ensemble des données fournie par le côté client. 
     * (information nécessaire sur l'évaluation pour la modification)
     * @return array un tableau d'évaluations
    */
    public function update_evaluation(array $data) : array
    {
        $teams_id = $this->find_teams_id($data['stand_id']);

        $sql = "UPDATE evaluation SET teams_id = :updated_teams_id, survey_id = :updated_surveyId
                WHERE id = :id;";

        $query = $this->connection->prepare($sql);
        $query->execute(array(
            ":id" => $data['id'],

            ":updated_teams_id" => $teams_id['id'],
            ":updated_surveyId" => $data['survey_id'],
        ));

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return $results;

    }

    /**
     * @author Xavier Houle
    * Fonction qui supprime une évaluation
    * @param $data L'ensemble des données fournie par le client
    * @return ?bool retourne si la supression s'est effectué
    */
    public function delete_evaluation(int $id) : ?bool
    {
        try
		{
			$sql = "DELETE FROM evaluation WHERE id= :id;";
			$req = $this->connection->prepare($sql);

			$req->execute(array(
				":id" => $id,
			));

			return $req->rowCount() >= 0;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);

			return null;
		}
    }

    /**
     * @author Xavier Houle
     * fonction pour trouver le teams_id en fonction du team_number
     * @param int $team_number Le numéro du stand.
     * @return array Le résultat de la requête.
     */
    public function find_teams_id(string $team_number) : array
    {
        $sql = "SELECT id, categories_id FROM teams WHERE team_number = :team_number;";
        
        $query = $this->connection->prepare($sql);
        $query->execute(array(
            ":team_number" => $team_number
        ));
        $response = $query->fetch();
        return $response;
    }
    
    /**
     * @author Xavier Houle (Auteur principal inconnu)
     * Fonction pour selectionner l'ensemble des plages horaires.
     * @return array un tableau des heures
    */
    public function selectTimeSlots() : array
    {
        $sql = "SELECT id, time FROM time_slots;";

        $query = $this->connection->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * Met à jour le champ `global_score_removed` pour un juge.
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Repository de manière à respecter la structure du projet.
     * @param int $judge_id L'ID du juge concerné.
     * @param bool $globalScoreRemoved La nouvelle valeur du champ.
     * @return bool Résultat de la mise à jour.
     */
    public function updateGlobalScoreRemoved(int $judge_id, array $body): bool
    {
        try {
            $sql = "UPDATE evaluation SET global_score_removed = :globalScoreRemoved WHERE judge_id = :judge_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':globalScoreRemoved', $body["global_score_removed"], PDO::PARAM_BOOL);
            $stmt->bindParam(':judge_id', $judge_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logHandler->error('Erreur lors de la mise à jour de la valeur de la note globale', [
                'error' => $e->getMessage(),
                'judge_id' => $judge_id,
                'globalScoreRemoved' => $body["global_score_removed"]
            ]);

            return false;
        }
    }

    /**
     * Récupère les états d'exclusion de score de toutes les évaluations.
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Repository de manière à respecter la structure du projet.
     * @return array Un tableau contenant les états d'exclusion des scores.
     */
    public function fetchScoreExclusions(): array
    {
        $sql = "SELECT judge_id, global_score_removed FROM evaluation";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        /** 
     * @author Xavier Houle
     * Fonction pour modifier les heures de passages.
     * @param array l'ensemble des données fournie par le côté client. 
     * (information nécessaire sur les heures de passages pour la modification)
     * @return bool si la modification a bien été effectuée
    */
    public function update_time_slots(array $data) : bool
    {
        try {
            $array = $data["hours"];

            foreach($array as $hour) {

                $sql = "UPDATE time_slots SET time = :time
                WHERE id = :id;";

                $query = $this->connection->prepare($sql);
                $query->execute(array(
                    ":id" => $hour['id'],

                    ":time" => $hour['time'],
                ));
            }

            return true;
        }
        catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);

			return false;
		}

    }

    
    /**
     * Fonction qui permet d'insérer un time_slot dans la base de donnée.
     * 
     * @author Alexis Boivin
     * 
     */
    public function add_time_slot(array $data){
        try {
            $array = $data["hours"];
            foreach($array as $hour) {

                $sql = "INSERT INTO time_slots (time) VALUES (:time);";

                $query = $this->connection->prepare($sql);
                $query->execute(array(
                    ":time" => $hour['time'],
                ));
            }

            return true;
        }
        catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);

			return false;
		}
    }
     /**
     * Fonction qui permet de supprimer un time_slot dans la base de donnée.
     * 
     * @author Alexis Boivin
     * 
     */
    public function delete_time_slot(){
        try {

            $sql = "DELETE FROM time_slots WHERE id = (SELECT id FROM (SELECT MAX(id) AS id FROM time_slots) AS table_temporaire);";

            $query = $this->connection->prepare($sql);
            $query->execute();

            return true;
        }
        catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);

			return false;
		}
    }

}