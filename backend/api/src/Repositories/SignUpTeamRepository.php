<?php

namespace App\Repositories;

use App\Enums\EnumHttpCode;
use App\Models\Team;
use App\Models\Result;
use PDOException;
use Test\TestsUtils\TestLogger;

/**
 * Classe SignUpTeamRepository.php
 * @package App\Repositories
 * @author Tristan Lafontaine
 */

class SignUpTeamRepository extends Repository
{    
    //Tableau de messages d'erreur
    private $errorMessages = [];

    /**
     * add_team
     * Ajout d'une équipe au complet
     * @param Team $team L'équipe à ajouter
     * @param array $token Le token de connexion
     * @return Result
     */
    public function add_team(Team $team, array $token):Result
    {
        try{
            $categoryArray= $this->get_category($team->category);
            if(sizeOf($categoryArray) < 0){
                return new Result(EnumHttpCode::BAD_REQUEST, array("Une ereur est survenue lors de la récupération des catégories"));
            }
            $sql = "INSERT INTO teams (team_number,name,description,years,categories_id, activated,creation_date, survey_id) VALUES (:team_number,:name,:description,:years,:categories_id, 1,CURRENT_TIMESTAMP,:survey_id);";
            $req = $this->db->prepare($sql);
            $req->execute(
                array(
                'team_number' => $team->team_number, 
                'name' => $this->uppercase_first_letter($team->title), 
                'description' => $team->description,
                'years' => $team->year,
                'categories_id' => $categoryArray["id"],
                'survey_id' => $categoryArray["survey_id"]
                )
            );
            //Obtenir le dernier id insérer
            $id = $this->db->lastInsertId();
            //Permet d'obtenir l'équipe qui vient d'être insérer
            $teamVerification = $this->get_team((int)$id);
            //Vérification que l'équipe est bien enregistré dans la bd
            if($teamVerification['id'] != null){
                //Ajout des membres de l'équipe dans la bd
                $this->add_member($team, $teamVerification['id'],$token);
                $this->add_contact_person($team);
                $this->add_team_contact_person($team->contactPerson,$teamVerification['id']);
                if(isset($this->errorMessages)){
                    if(sizeof($this->errorMessages) > 0){
                        $this->delete_team($teamVerification['id']);
                        return new Result(EnumHttpCode::BAD_REQUEST, array("Il a eu une erreur lors de l'ajout"), $this->errorMessages);
                    }
                }
                return new Result(EnumHttpCode::CREATED, array("L'ajout de l'équipe a réussi."));
            }
            return new Result(EnumHttpCode::BAD_REQUEST, array("L'équipe n'a pas peu être ajouté."));
        }
        catch(PDOException $e) {
            $this->errorMessages[] = "addTeam: " . $e->getMessage();
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Il a eu une erreur lors de l'ajout"));
        }
    }
      
    /**
     * add_member
     * Permet d'ajouter des membres
     * @param  Team $team L'équipe à ajouter
     * @param  int $idTeam L'id de l'équipe
     * @param array $token Le token de connexion
     * @return void
     */
    public function add_member(Team $team, int $teamId, array $token){
        //Permet d'obtenir la longueur du tableau
        $sizeofArray = sizeof($team->members);
        //Permet de boucler dans le tableau des membres
        for($a = 0; $a < $sizeofArray; $a++){
            try{
                //Permet de vérifier si le membre est déjà inscrit
                $verifcationMember = $this->get_member_by_numero_da($team->members[$a]["numero_da"]);
                if(sizeOf($verifcationMember) == 0){ //
                    //Insertion d'un membre dans la bd
                    $sql = "INSERT INTO users (first_name, last_name, numero_da, role_id, picture_consent, activated, activation_token) VALUES (:first_name, :last_name, :numero_da, :role_id, :picture_consent, 0, :activation_token)";
    
                    $req = $this->db->prepare($sql);
                    $req->execute(array(
                        "first_name" => $this->uppercase_first_letter ($team->members[$a]['firstName']),
                        "last_name" => $this->uppercase_first_letter($team->members[$a]['lastName']),
                        "numero_da" => $team->members[$a]['numero_da'],
                        "role_id" => 3,
                        "picture_consent" => $team->members[$a]['pictureConsent'],
                        "activation_token" => $token[$a]
                    ));
                }
                //Permet de mettre à jour le token pour une nouvelle validation
                else{
                    $sql = "UPDATE users SET activation_token = :activation_token WHERE id = :id";
                    $req = $this->db->prepare($sql);
                    $req->execute(array(
                        "activation_token" => $token[$a],
                        "id" => $verifcationMember['id']
                    ));
                }
                //Permet d'obtenir les informations d'un membre dans la bd
                $memberVerification = $this->get_member_by_numero_da($team->members[$a]['numero_da']);
                //Vérification que le membre est bien dans la bd
                if($memberVerification['id'] == null){
                    //Suppresion de l'équipe si le membre n'est pas ajouter dans l'équipe
                    $this->delete_team($teamId);
                    return new Result(EnumHttpCode::BAD_REQUEST, array("Les membres n'ont pas été ajouté."));
                }
                else{
                    //Permet d'ajouter de relier un membre de l'équipe à une équipe
                    $this->add_users_teams($teamId,$memberVerification['id']);
                }
            }
            catch(PDOException $e) {
                $this->errorMessages[] = "addMember: " . $e->getMessage();
                $context["http_error_code"] = $e->getCode();
                $this->logHandler->critical($e->getMessage(), $context);
            }
        }
    }
    
    /**
     * add_users_teams
     * Ajout les membres de l'équipe dans une équipe
     * @param  int $teamId l'id de l'équipe
     * @param  int $userId L'id du membre
     * @return void
     */
    public function add_users_teams(int $teamId, int $userId){
        try{
            $sql = "INSERT INTO users_teams(teams_id,users_id) VALUES (:team_id,:user_id)";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "team_id" => $teamId,
                "user_id" => $userId
            ));
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "addUsersTeams: " . $e->getMessage();
        }
    }
    
    /**
     * add_team_contact_person
     * Permet d'ajouter des personnes ressources dans une équipe
     * @param  array $contactPerson Tableau de personne ressource
     * @param  int $teamId L'id de l'équipe
     * @return void
     */
    public function add_team_contact_person(array $contactPerson, int $teamId){
        try{
            $sizeofArray = sizeof($contactPerson);
            for($a = 0; $a < $sizeofArray; $a++){
                $contactPersonResultat = $this->get_contact_person_by_email($contactPerson[$a]["email"]);
                $sql = "INSERT INTO teams_contact_person (teams_id,contact_person_id) VALUES (:teams_id, :contact_person_id)";
                $req = $this->db->prepare($sql);
                $req->execute(array(
                    "teams_id" => $teamId,
                    "contact_person_id" => $contactPersonResultat['id']
                ));
            }
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "addTeamContactPerson: " . $e->getMessage();
        }
    }
        
    /**
     * add_contact_person
     * Ajout d'une personne ressource dans la base de données
     * @param  Team $team L'équipe à ajouter
     * @return void
     */
    public function add_contact_person(Team $team){
        try{
            $sizeofArray = sizeof($team->contactPerson);
            for($a = 0; $a < $sizeofArray; $a++){
                $emailContact = $this->get_contact_person_by_email($team->contactPerson[$a]["email"]);
                if(sizeOf($emailContact) == 0){
                    $sql = "INSERT INTO contact_person (name,email) VALUES (:name,:email)";
                    $req = $this->db->prepare($sql);
                    $req->execute(array(
                        "name" => $team->contactPerson[$a]["fullName"],
                        "email" => $team->contactPerson[$a]['email']
                    ));
                }
            
            }
        }catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "addContactPerson: " . $e->getMessage();
        }
    }

    /**
     * get_category
     * Permet d'obtenir l'id et le survey_id d'une categorie
     * @param  string $category Le nom de la catégorie
     * @return array|null
     */
    public function get_category(string $category)
    {
        try{
            $sql = "SELECT id,survey_id FROM categories WHERE name = :category";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "category" => $category
            ));
            $response = $req->fetch();
			return !$response ? null : $response;
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getCategory: " . $e->getMessage();
            return null;
        }
        
    }

    /**
     * get_contact_person_by_email
     * Permet de retourner une personne ressource à partir de son courriel
     * @param  string $email L'adresse courriel de la personne ressource
     * @return array
     */
    public function get_contact_person_by_email(string $email) : array
    {
        try{
            $sql = "SELECT id,name,email FROM contact_person WHERE email = :email";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "email" => $email
            ));
            $response = $req->fetch();
            if(!$response){
                return array();
            }
            return $response;
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getContactPersonByEmail: " . $e->getMessage();
            return array();
        }
    }

    /**
     * get_all_team_by_title_and_description
     * Fonction qui permet d'obtenir la liste 
     * @param  string $title Le titre du l'équipe
     * @param  string $description La description de l'équipe
     * @return array
     */
    public function get_all_team_by_title_and_description(string $title, string $description) : array 
    {
        try{
            $sql = "SELECT id,team_number,name,description,categories_id,creation_date,survey_id FROM teams WHERE name = :title AND description = :description";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "title" => $title,
                "description" => $description
            ));
            return $req->fetchAll();
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getAllTeam: " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * get_team_by_title_description_and_category
     *
     * Permet d'obtenir un id selon le titre, sa description et sa catégorie
     * @param  string $title Le titre de l'équipe
     * @param  string $description La description du l'équipe
     * @param  string $category La categorie du l'équipe'
     * @return int|null
     */
    public function get_team_by_title_description_and_category(string $title, string $description, string $category)
    {
        try{
            $sql = "SELECT id,team_number,name,description,categories_id,creation_date,survey_id FROM teams WHERE name = :title AND description = :description AND categories_id = :categories_id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "title" => $title,
                "description" => $description,
                "categories_id" => $category
            ));
            $response = $req->fetch();
			return !$response ? null : $response["id"];
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getAllTeam: " . $e->getMessage();
            return null;
        }
    }

    /**
     * get_team
     * Permet d'obtenir une équipe à partir d'un id de l'équipe
     * @param int id ID de l'équipe
     * @return array
     */
    public function get_team(int $id) : array
    {
        try{
            $sql = "SELECT id,team_number,name,description,categories_id,creation_date,survey_id FROM teams WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "id" => $id
            ));
            $response = $req->fetch();
			return !$response ? array() : $response;
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getTeam: " . $e->getMessage();
            return array();
        }
    }
    
    /**
     * get_member_by_numero_da
     * Permet d'obtenir un membre à partir de son numéro de DA
     * @param  string $numero_da Le numéro de DA du membre
     * @return array 
     */
    public function get_member_by_numero_da(string $numero_da) : array
    {
        try{
            $sql = "SELECT id,first_name, last_name, numero_da, role_id FROM users WHERE numero_da = :numero_da";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                'numero_da' => $numero_da
            ));
            $result = $req->fetch();
            if($result == false){
                return array();
            }else{
                return $result;
            }
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getMemberByNumeroDa: " . $e->getMessage();
            return array();
        }
    }
    
    /**
 * get_member_by_numero_da_and_survey
 * Fonction qui permet de savoir si un étudiant est déjà inscrit selon la catégorie.
 * @param  string $numero_da Le numéro de DA du membre
 * @param  string $category Le nom de la catégorie
 * @return array
 */
public function get_member_by_numero_da_and_survey(string $numero_da, string $category) : array
{
    $category = $this->get_category($category);
    try{
        $sql = "SELECT survey_id, users.numero_da FROM users_teams
                INNER JOIN teams ON teams_id = teams.id
                INNER JOIN users ON users_id = users.id WHERE numero_da = :numero_da AND survey_id = :survey_id;";
        $req = $this->db->prepare($sql);
        $req->execute(array(
            'numero_da' => $numero_da,
            'survey_id' => $category["survey_id"]
        ));
        $result = $req->fetch();
        if($result == false){
            return array();
        }else{
            return $result;
        }
    }
    catch(PDOException $e) {
        $context["http_error_code"] = $e->getCode();
        $this->logHandler->critical($e->getMessage(), $context);
        $this->errorMessages[] = "get_member_by_numero_da_and_survey: " . $e->getMessage();
        return array();
    }
}
    
    /**
     * get_members_team
     * Permet d'obtenir tous les membres dans une équipe
     * @param  int $id L'id de l'équipe
     * @return array
     */
    public function get_members_team(int $id) : array
    {
   
        try {
            $sql = "SELECT users.id FROM users 
                INNER JOIN users_teams ON users.id = users_teams.users_id 
                WHERE teams_id = :team_id GROUP BY users_id;";

            $req = $this->db->prepare($sql);
            $req->execute(["team_id" => $id]);
            $result = $req->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        }
        catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            return [];
        }
    }

    /**
     * get_max_members_category
     * Requête pour obtenir le nombre maximum de coéquipier par catégorie pour une équipe
     * @param string $category Le nom de la catégorie
     * @return array
     */
    public function get_max_members_category(string $category) : array
    {
        try{
            $sql = "SELECT max_members FROM categories WHERE activated = 1 and name = :category";
            $req = $this->db->prepare($sql);
    
            $req->execute(array(
                "category" => $category
            ));
    
            $user_data = $req->fetch();
    
            if(!is_array($user_data))
            {
                return array();
            }
            return $user_data;
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "getMaxMembersCategory: " . $e->getMessage();
            return array();
        }
    }
        
    /**
     * delete_all_team
     * Permet de supprimer toutes les équipes
     * @param  array $ids L'id des équipes
     * @return array
     */
    public function delete_all_team(array $ids) : array
    {
        foreach ($ids as $id) {
            $this->delete_team($id);
        }
        if(sizeof($this->errorMessages) === 0){
            return array();
        }else{
            return $this->errorMessages;
        }
    }

    /**
     * delete_team
     * Permet de supprimer une équipe à partir de l'id de l'équipe
     * @param  int $id L'id de l'équipe
     * @return string
     */
    public function delete_team(int $id): bool
    {
        try {
            // Suppression des relations entre l'équipe et ses membres
            $this->delete_users_team($id);

            // Suppression des relations entre l'équipe et les personnes ressources
            $this->delete_teams_contact_person($id);

            // Suppression de l'équipe
            $sql = "DELETE FROM teams WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(["id" => $id]);

            return $req->rowCount() > 0;
        }
        catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * delete_all_team_members
     * Permet de supprimer tous les membres d'une équipe
     * @param  array $ids Tableau d'id des membres
     * @return array
     */
    public function delete_all_team_members(array $ids) : array
    {
        for($i = 0; $i < sizeof($ids); $i++){
            $this->delete_teams_users($ids[$i]);
            $this->delete_user($ids[$i]);
        }
        if(sizeof($this->errorMessages) == 0){
            return array();
        }else{
            return $this->errorMessages;
        }
    }

    /**
     * delete_all_member_team
     * Supprime tous les membres d'une équipe à partir d'une liste d'id
     * @param  array $members Tableau de membres
     * @return void
     */
    public function delete_all_member_team(array $members){
        $sizeofArray = sizeof($members);
        for($a = 0; $a < $sizeofArray; $a++){
            $this->delete_user($members[$a]["id"]);
        }
    }

    /**
     * delete_user
     * Permet de supprimer un membre d'un équipe
     * @param  int $userId L'id du membre
     * @return void
     */
    public function delete_user(int $userId){
        try{
            $sql = "DELETE FROM users WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "id" => $userId,
            ));
        }catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "deleteUser: " . $e->getMessage();
        }
        
    }
    
    /**
     * delete_users_team
     * Suppression de tous les liens entre une équipe et ses membres
     * @param  int $teamId L'id de l'équipe
     * @return void
     */
    public function delete_users_team(int $teamId){
        try{
            
            $sql = "DELETE FROM users_teams WHERE teams_id = :team_id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "team_id" => $teamId
            ));
        }catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "deleteUsersTeam: " . $e->getMessage();
        }
    }

    /**
     * delete_teams_users
     * Suppression de tous les liens entre une équipe et ses membres
     * @param  int $usersId L'id du membres
     * @return void
     */
    public function delete_teams_users(int $usersId){
        try{
            
            $sql = "DELETE FROM users_teams WHERE users_id = :users_id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "users_id" => $usersId
            ));
        }catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "deleteUsersTeam: " . $e->getMessage();
        }
    }
    
    /**
     * delete_teams_contact_person
     * Supression de tous les liens entre une personne ressource et une équipe.
     * @param  int $teamId L'id de l'équipe
     * @return void
     */
    public function delete_teams_contact_person(int $teamId){
        try{
            $sql = "DELETE FROM teams_contact_person WHERE teams_id = :team_id";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "team_id" => $teamId,
            ));
        }catch(PDOException $e){
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "deleteTeamsContactPerson: " . $e->getMessage();
        }
    }
    
    /**
 * check_numero_da_is_not_BD
 * Vérification des numéros de DA dans la base de données.
 * @param  Team $team L'équipe
 * @return array
 */
public function check_numero_da_is_not_BD(Team $team): array
{
    $errorNumeroDa = [];
    $sizeofArray = sizeof($team->members);
    for($a = 0; $a < $sizeofArray; $a++){
        $numeroDa = $this->get_member_by_numero_da_and_survey($team->members[$a]["numero_da"], $team->category);
        if($numeroDa != null){
            $errorNumeroDa[] =  "Le numéro de DA est déjà utilisé : " . $numeroDa["numero_da"];
        }
    }
    return $errorNumeroDa;
}
    
    /**
 * check_numero_da_duplicate
 * Vérifie si il n'y a pas écrit plus d'une fois dans le formulaire le même numéro de DA
 * @param  Team $team L'équipe
 * @return array
 */
public function check_numero_da_duplicate(Team $team) : array
{
    $errorNumeroDa = [];
    $sizeofArray = sizeof($team->members);
    for($a = 0; $a < $sizeofArray; $a++){
        for($b = $a +1; $b < $sizeofArray; $b++){
            if($team->members[$a]["numero_da"] == $team->members[$b]["numero_da"]){
                $errorNumeroDa[] = "Vous utilisez le même numero DA : " . $team->members[$a]["numero_da"];
            }
        }
    }
    return $errorNumeroDa;
}
    
    /**
     * check_team_active
     * Fonction qui vérifie si l'équipe est active ou pas selon le numéro de DA de deux membres de l'équipe.
     * @param  Team $team L'équipe
     * @return array
     */
    public function check_team_active(Team $team) : array
    {
        $response = [];

        try {
            for ($a = 0; $a < sizeof($team->members); $a++) {
                $sql = "SELECT * FROM users WHERE activation_token IS NOT NULL and numero_da = :numero_da";
                $req = $this->db->prepare($sql);
                $req->execute(array(
                    "numero_da" => $team->members[$a]["numero_da"],
                ));
                $fetch = $req->fetch();
                $response[] = !$fetch ? [] : $fetch;
            }
            return $response;
        }
        catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            $this->errorMessages[] = "checkTeamActive : " . $e->getMessage();
        }
    }

    /**
     * uppercase_first_letter
     * Permet de mettre la première lettre à majuscule.
     * @param string $data Une donnée en string
     * @return string
     */
    public function uppercase_first_letter(string $data):string
    {
        return ucfirst($data);
    }
    
}