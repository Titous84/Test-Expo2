<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\TeamInfo;
use App\Models\TeamMember;
use PDOException;

/**
 * Class TeamsListRepository
 * @author Tristan Lafontaine, Carlos Cordeiro
 * @package App\Repositories
 */
class TeamsListRepository extends Repository
{
    /**
     * Fonction qui permet d'obtenir tous les membres et les équipes
     * @param  string $role_name
     * @return array Retourne un tableau contenant les équipes et les membres ou un tableau vide.
     */
    public function get_all_teams_and_members(string $role_name): array
    {
        try {
            $sql = "SELECT users.id,
                teams.id as team_id,
                teams.team_number,
                teams.name as title,
                teams.description,
                categories.name as category,
                teams.years as year,
                survey.name as survey,
                teams.activated as teams_activated, 
                users.first_name, 
                users.last_name,
                users.numero_da,
                users.email, 
                users.activated as users_activated,
                users.blacklisted, 
                users.picture_consent,
                GROUP_CONCAT(DISTINCT contact_person.name SEPARATOR ', ') as contact_person_name, 
                GROUP_CONCAT(DISTINCT contact_person.email SEPARATOR ', ') as contact_person_email
            FROM users 
            INNER JOIN users_teams ON users.id = users_teams.users_id 
            INNER JOIN teams ON teams.id = users_teams.teams_id 
            INNER JOIN categories ON teams.categories_id = categories.id 
            INNER JOIN survey ON teams.survey_id = survey.id
            INNER JOIN role ON users.role_id = role.id
            INNER JOIN teams_contact_person ON teams.id = teams_contact_person.teams_id
            INNER JOIN contact_person ON teams_contact_person.contact_person_id = contact_person.id
            WHERE role.name = :role_name
            GROUP BY users.id, teams.id";

            $req = $this->db->prepare($sql);

            $req->execute(
                array(
                    "role_name" => $role_name
                )
            );

            $response = $req->fetchAll();

            if (!$response) {
                return array();
            }

            return $response;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return [];
        }
    }

    /**
     * Fonction qui permet d'obtenir tous les équipes avec les membres dans une seul colonne
     * @param  string $role_name
     * @return array Retourne un tableau contenant les équipes et les membres ou un tableau vide.
     */
    public function get_all_teams_and_members_concat(string $role_name): array
    {
        try {
            $sql = "SELECT teams.id as team_id, teams.team_number, teams.name as title, teams.description, categories.name as category, teams.years as year, survey.name as survey, teams.activated as teams_activated, 
            GROUP_CONCAT(DISTINCT CONCAT(users.first_name,' ',users.last_name) SEPARATOR ', ') as members, GROUP_CONCAT(DISTINCT contact_person.name SEPARATOR ', ') as contact_person_name, GROUP_CONCAT(DISTINCT contact_person.email SEPARATOR ', ') as contact_person_email
            FROM users 
            INNER JOIN users_teams ON users.id = users_teams.users_id 
            INNER JOIN teams ON teams.id = users_teams.teams_id 
            INNER JOIN categories ON teams.categories_id = categories.id 
            INNER JOIN survey ON teams.survey_id = survey.id
            INNER JOIN role ON users.role_id = role.id
            INNER JOIN teams_contact_person ON teams.id = teams_contact_person.teams_id
            INNER JOIN contact_person ON teams_contact_person.contact_person_id = contact_person.id
            WHERE role.name = :role_name
            GROUP BY teams.id
            ORDER BY teams.id;";

            $req = $this->db->prepare($sql);

            $req->execute(
                array(
                    "role_name" => $role_name
                )
            );

            $response = $req->fetchAll();

            if (!$response) {
                return array();
            }

            return $response;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return [];
        }
    }

    /**
     * Fonction qui permet d'obtenir toutes les informations ainsi que les membres d'une équipe
     * @param int $id
     * @return Team | null Retourne une équipe ou null si l'équipe n'est pas trouvé ou n'existe pas.
     */
    public function get_team_and_members(int $id): ? Team
    {
        try {
            $sql = "SELECT users.id, teams.id as team_id, teams.team_number, teams.name as title, teams.description, categories.name as category, teams.years as year, survey.name as survey, survey.id as survey_id, teams.activated as teams_activated, 
            users.first_name, users.last_name, users.numero_da, users.email, users.picture_consent, users.activated as users_activated, users.blacklisted, contact_person.name as contact_name, contact_person.email as contact_email, contact_person.id as contact_id
            FROM users 
            INNER JOIN users_teams ON users.id = users_teams.users_id 
            INNER JOIN teams ON teams.id = users_teams.teams_id 
            INNER JOIN categories ON teams.categories_id = categories.id 
            INNER JOIN survey ON teams.survey_id = survey.id
            INNER JOIN teams_contact_person ON teams.id = teams_contact_person.teams_id
            INNER JOIN contact_person ON teams_contact_person.contact_person_id = contact_person.id
            WHERE teams.id = :id
            ORDER BY teams.id;";

            $request = $this->db->prepare($sql);
            $request->execute(
                ["id" => $id]
            );

            $response = $request->fetchAll();

            if (!$response) {
                return null;
            }

            return $this->construct_teams_from_array($response);
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return null;
        }
    }

    /**
     * Fonction qui permet d'obtenir l'id et le nom du type d'évaluation à partir du nom de l'évaluation
     * @param  string $survey
     * @return array Retourne un tableau contenant l'id et le nom du type d'évaluation ou un tableau vide.
     */
    public function get_survey_by_name(string $survey): array
    {
        try {
            $sql = "SELECT id, name FROM survey WHERE name = '$survey'";

            $req = $this->db->query($sql);

            $req->execute();

            $response = $req->fetchAll();

            if (!$response) {
                return array();
            }

            return $response;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return [];
        }
    }

    /**
    * Fonction qui récupère le nom de toutes les catégories
    * @return array Retourne un tableau contenant l'id et le nom des catégories
    */
    public function get_categories(): array 
    {
        try {
            $sql = "SELECT id, name FROM categories ORDER BY name";

            $req = $this->db->query($sql);
            $req->execute();

            $response = $req->fetchAll();

            if (!$response) {
                return array();
            }

            return $response;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return [];
        }
    }


    /**
     * Fonction qui permet d'obtenir l'id et le nom de la catégorie à partir du nom de la catégorie
     * @param  string $category
     * @return array Retourne un tableau contenant l'ID,le nom de la catégorie et l'ID de template d'évaluation ou un tableau vide.
     */
    public function get_category_by_name(string $category): array
    {
        try {
            $sql = "SELECT id, name, survey_id FROM categories WHERE name = :category";
    
            $req = $this->db->prepare($sql);
            $req->execute(["category" => $category]);
    
            $response = $req->fetchAll();
    
            if (!$response) {
                return [];
            }
    
            return $response;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            return [];
        }
    }

    /**
     * Fonction qui permet d'ajouter un membre à une équipe
     * @param  TeamMember $teamMember
     * @return bool Retourne vrai si l'ajout a fonctionné sinon, il retourne faux
     */
    public function add_team_member(TeamMember $teamMember): bool
    {
        try {
            $this->db->beginTransaction();

            // Insertion dans la table `users`
            $sqlUser = "
                INSERT INTO users (first_name, last_name, numero_da, picture_consent, activated, role_id)
                VALUES (:first_name, :last_name, :numero_da, :picture_consent, :activated, :role_id)
            ";

            $reqUser = $this->db->prepare($sqlUser);
            $reqUser->execute([
                "first_name" => $teamMember->firstName,
                "last_name" => $teamMember->lastName,
                "numero_da" => $teamMember->numeroDa,
                "picture_consent" => $teamMember->pictureConsent,
                "activated" => $teamMember->userActivated,
                "role_id" => 3,
            ]);

            // Récupérer l'ID du membre inséré
            $userId = $this->db->lastInsertId();

            if (!$userId) {
                $this->db->rollBack();
                return false;
            }

            $sqlUserTeam = "
                INSERT INTO users_teams (users_id, teams_id)
                VALUES (:users_id, :teams_id)
            ";

            $reqUserTeam = $this->db->prepare($sqlUserTeam);
            $reqUserTeam->execute([
                "users_id" => $userId,
                "teams_id" => $teamMember->teamId,
            ]);

            if ($reqUserTeam->rowCount() === 0) {
                $this->db->rollBack();
                return false;
            }

            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return false;
        }
    }

    /**
     * Fonction qui permet de mettre à jour les informations de l'équipe et de mettre à jour les informations de l'enseignant(e) de l'équipe
     * @param  TeamInfo $team
     * @return bool Retourne vrai si la mise à jour a fonctionné sinon, il retourne faux
     */
    public function update_team_info(TeamInfo $team): bool
    {
        try {
            $responseCategory = $this->get_category_by_name($team->category);
    
            if (sizeof($responseCategory) == 0) {
                return false;
            }
    
            // Récupération de survey_id associé à la catégorie
            $surveyId = $responseCategory[0]["survey_id"] ?? null;
    
            if ($surveyId === null) {
                return false;
            }
    
            // Requête pour mettre à jour les informations de l'équipe
            $sql = "UPDATE teams 
                    SET team_number = :team_number, 
                        name = :name, 
                        description = :description, 
                        categories_id = :categories_id, 
                        years = :years, 
                        survey_id = :survey_id, 
                        activated = :activated 
                    WHERE id = :id";
    
            $req = $this->db->prepare($sql);
    
            $req->execute([
                "id" => $team->id,
                "team_number" => $team->teamNumber,
                "name" => $team->title,
                "description" => $team->description,
                "categories_id" => $responseCategory[0]["id"],
                "years" => $team->year,
                "survey_id" => $surveyId,
                "activated" => $team->teamsActivated
            ]);
    
            if ($req->rowCount() == 0) {
                return false;
            }

            return true;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            return false;
        }
    }

    /**
     * Fonction qui permet de mettre à jour les membres de l'équipe
     * @param  TeamMember $teamMember
     * @return bool Retourne vrai si la mise à jour a fonctionné sinon, il retourne faux
     */
    public function update_team_member(TeamMember $teamMember): bool
    {
        try {
            $this->db->beginTransaction();
    
            // Update des infos du membre
            $sqlUser = "
            UPDATE users
            SET
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                numero_da = :numero_da,
                blacklisted = :blacklisted,
                picture_consent = :picture_consent,
                activated = :activated
            WHERE id = :id";
    
            $reqUser = $this->db->prepare($sqlUser);
            $reqUser->execute([
                "id" => $teamMember->id,
                "email" => $teamMember->email,
                "first_name" => $teamMember->firstName,
                "last_name" => $teamMember->lastName,
                "numero_da" => $teamMember->numeroDa,
                "blacklisted" => $teamMember->blacklisted,
                "picture_consent" => $teamMember->pictureConsent,
                "activated" => $teamMember->userActivated
            ]);
    
            // Vérification si la relation existe déjà dans la table users_teams
            if (isset($teamMember->teamId)) {
                $sqlCheck = "
                    SELECT COUNT(*) as count
                    FROM users_teams
                    WHERE users_id = :user_id AND teams_id = :team_id;
                ";
    
                $reqCheck = $this->db->prepare($sqlCheck);
                $reqCheck->execute([
                    "user_id" => $teamMember->id,
                    "team_id" => $teamMember->teamId
                ]);
    
                $result = $reqCheck->fetch();
                if ($result['count'] > 0) {
                } else {
                    // Mise à jour ou insertion de la relation
                    $sqlUpdate = "
                        UPDATE users_teams
                        SET teams_id = :team_id
                        WHERE users_id = :user_id;
                    ";
    
                    $reqUpdate = $this->db->prepare($sqlUpdate);
                    $reqUpdate->execute([
                        "user_id" => $teamMember->id,
                        "team_id" => $teamMember->teamId
                    ]);
    
                    if ($reqUpdate->rowCount() === 0) {
                        $sqlInsert = "
                            INSERT INTO users_teams (users_id, teams_id)
                            VALUES (:user_id, :team_id);
                        ";
                        $reqInsert = $this->db->prepare($sqlInsert);
                        $reqInsert->execute([
                            "user_id" => $teamMember->id,
                            "team_id" => $teamMember->teamId
                        ]);
                    }
                }
            } else {
            }
    
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            return false;
        }
    }

    /**
     * Fonction qui permet de mettre à jour les numéros des stands de l'équipe
     * @param  array $teams
     * @return bool Retourne vrai si la mise à jour a fonctionné sinon, il retourne faux
     */
    public function update_teams_numbers(array $teams): bool
    {
        try {
            $sizeofArray = sizeof($teams);
            for($a = 0; $a < $sizeofArray; $a++){

                $sql = "UPDATE teams SET team_number = :team_number WHERE id = :id";

                $req = $this->db->prepare($sql);

                $req->execute(
                    array(
                        "team_number" => $teams[$a]["team_number"],
                        "id" => $teams[$a]["team_id"]
                    )
                );

                // echo $req->rowCount();
                if ($req->rowCount() == 0) {
                    return false;
                }
            }
            return true;
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return false;
        }
        
    }

    /**
     * Contruit une équipe à partir des valeurs d'un array.
     * @param array $array Un tableau des membres.
     * @return Team | null Retourne l'équipe de l'array.
     */
    private function construct_teams_from_array(array $members): ? Team
    {
        if (count($members) < 1)
            return null;

        $teamMembers = [];

        foreach ($members as $member) {
            array_push(
                $teamMembers,
                new TeamMember(
                    [
                        "id" => $member["id"],
                        "email" => $member["email"],
                        "first_name" => $member["first_name"],
                        "last_name" => $member["last_name"],
                        "blacklisted" => $member["blacklisted"],
                        "activated" => $member["users_activated"],
                        "contact_person_email" => $member["contact_email"],
                        "contact_person_name" => $member["contact_name"],
                        "contact_person_id" => $member["contact_id"],
                        "picture_consent" => $member["picture_consent"],
                    ]
                )
            );
        }

        $team = new Team(
            [
                "id" => $members[0]["team_id"],
                "team_number" => $members[0]["team_number"],
                "title" => $members[0]["title"],
                "description" => $members[0]["description"],
                "category" => $members[0]["category"],
                "survey" => $members[0]["survey"],
                "activated" => $members[0]["teams_activated"],
                "year" => $members[0]["year"],
                "contactPerson" => [
                    [
                        "fullName" => $members[0]["contact_name"],
                        "email" => $members[0]["contact_email"],
                    ]
                ],
                "type_id" => $members[0]["survey_id"],
                "members" => $teamMembers,
            ]
        );

        return $team;
    }
}