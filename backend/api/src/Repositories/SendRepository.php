<?php

namespace App\Repositories;

use PDO;
use PDOException;

/**
 * A supprimer sert a rien
 */
class SendRepository extends Repository
{
    public function sendResultats($id) : array
    {
        try
        {
            $sql = "SELECT teams.id, teams.name as teams_name, floor(Avg(global_score)) As global_note, contact_person.name, contact_person.email 
            FROM evaluation
            INNER JOIN judge ON judge.id = evaluation.judge_id
            INNER JOIN users ON users.id = judge.users_id
            INNER JOIN teams ON teams.id = evaluation.teams_id
            INNER JOIN categories ON categories.id = teams.categories_id
            INNER JOIN survey ON survey.id = evaluation.survey_id
            INNER JOIN teams_contact_person ON teams_contact_person.teams_id = teams.id
            INNER JOIN contact_person ON contact_person.id = teams_contact_person.contact_person_id
            WHERE teams.id = :id
            GROUP BY teams_name;";

            $query = $this->db->prepare($sql);
            $query->execute(array('id'=>$id));
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        }
        catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return [];
		}
    }
}

