<?php

namespace App\Repositories;

use App\Enums\EnumHttpCode;
use App\Models\User;
use App\Models\Judge;
use App\Models\Administrators\AdministratorToCreate;
use App\Models\Administrators\AdministratorToReturn;
use App\Models\Result;
use App\Models\UserRole;
use App\Utils\GeneratorUUID;
use PDOException;

/**
 * Classe UserRepository
 * @author Christopher Boisvert
 * @author Alex Des Ruisseaux
 * @author Mathieu Sévégny
 * @package App\Repositories
 */
class UserRepository extends Repository
{
	/**
	 * Fonction qui permet d'obtenir tous les utilisateurs
	 * @author Christopher Boisvert
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|false Retourne un tableau des utilisateurs, sinon retourne false.
	 */
    public function get_all_users(): array
    {
		try
		{
			$sql = "SELECT id, first_name, last_name, username, pwd, email, picture, picture_consent, activated, blacklisted, role_id 
				FROM users";
			$req = $this->db->query($sql);
			return $req->fetchAll();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return [];
		}
    }

	/**
	 * Fonction qui permet d'obtenir tous les juges actifs.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @author Thomas-Gabriel Paquin
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|false Retourne un tableau des juges, sinon retourne false.
	 */
    public function get_all_judges()
    {
		try
		{
			$sql = "SELECT users.id, first_name as firstName, last_name as lastName, email, uuid, categories.name AS category, users.blacklisted, users.activated
			FROM users 
			INNER JOIN judge ON judge.users_id = users.id
			INNER JOIN categories ON judge.categories_id = categories.id
			WHERE role_id = 1";

			$req = $this->db->prepare($sql);
			$req->execute();
			return $req->fetchAll();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
    }

    /**
     * Récupère tous les administrateurs de la BD.
     * @author Antoine Ouellette
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return array Un tableau de tous les administrateurs.
     */
    public function get_all_administrators(): array
    {
        try
        {
            // Déclaration de la requête SQL.
            // Récupérer seulement les utilisateurs qui ont le rôle d'administrateur.
            $sqlRequest = "SELECT id, email FROM users WHERE role_id = 0"; // 0 est le rôle d'administrateur.

            // Exécuter la requête SQL.
            $sqlResponse = $this->db->query($sqlRequest);

            // Si la requête échoue, lancer une exception.
            if ($sqlResponse === false) {
                throw new PDOException("Une erreur est survenue lors de la récupération des administrateurs.");
            }

            // Retourner un tableau de toutes les rangées du résultat de la requête.
            return $sqlResponse->fetchAll();
        }
        catch(PDOException $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner un tableau vide. On ne connait pas les administrateurs.
            return [];
        }
    }

    /**
     * Récupère un administrateur de la BD par son id.
     * @param int $administratorId L'id de l'administrateur à récupérer.
     * @author Antoine Ouellette
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return ?object Un administrateur ou null si l'id n'existe pas.
     */
    public function get_administrator_by_id(int $administratorId): ?AdministratorToReturn
    {
        try
        {
            // Déclaration de la requête SQL (avec paramètres).
            $sqlRequest = "SELECT id, email FROM users WHERE id=:id AND role_id=0";

            // Préparer la requête SQL.
            $sqlStatement = $this->db->prepare($sqlRequest);

            if ($sqlStatement === false) {
                throw new PDOException("Erreur lors de la préparation de la requête.");
            }

            // Exécuter la requête SQL en liant la variable $administratorId au paramètre :id.
            $sqlStatement->execute(['id' => $administratorId]);

            // Récupérer le résultat (une seule rangée).
            $result = $sqlStatement->fetch(\PDO::FETCH_ASSOC);

            // Si l'administrateur avec ce id n'existe pas.
            if (!$result) {
                return null;
            }

            // Crée et retourne un objet AdministratorToReturn à partir du résultat (qui est un tableau associatif).
            return new AdministratorToReturn($result);
        }
        catch(PDOException $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner null. On n'a pas trouvé d'administrateur.
            return null;
        }
    }

    /**
     * Vérifie si l'email est déjà utilisé par un autre administrateur.
     * @param string $email L'email à vérifier.
     * @throws \PDOException Peut lancer des erreurs PDOException.
     * @return bool true si l'email est déjà utilisé, false sinon.
     */
    public function is_email_already_in_use(string $email): bool
    {
        try
        {
            // Déclaration de la requête SQL.
            $sqlRequest = "SELECT id FROM users WHERE email=:email";

            // Préparer la requête SQL.
            $sqlStatement = $this->db->prepare($sqlRequest);

            if ($sqlStatement === false) {
                throw new PDOException("Erreur lors de la préparation de la requête.");
            }

            // Exécuter la requête SQL en liant la variable $email au paramètre :email dans la requête SQL.
            $sqlStatement->execute(['email' => $email]);

            // Récupérer le résultat (une seule rangée).
            $result = $sqlStatement->fetch(\PDO::FETCH_ASSOC);

            // Si le résultat n'est pas vide, cela signifie que l'email a été trouvé dans la BD.
            return !empty($result);
        }
        catch(PDOException $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner false. On n'a pas trouvé d'administrateur.
            return false;
        }
    }

    /**
     * Crée un administrateur dans la BD.
     * @param AdministratorToCreate $administratorToCreate Un administrateur à créer.
     * @return bool true si l'administrateur a été créé avec succès, false sinon.
     */
    public function create_administrator(AdministratorToCreate $administratorToCreate): bool
    {
        try
        {
            // Déclaration de la requête SQL.
            $sqlRequest = "INSERT INTO users(email,pwd,role_id) VALUES(:email,:password,0)"; // 0 est le rôle d'administrateur.

            // Préparer la requête SQL.
            $sqlStatement = $this->db->prepare($sqlRequest);

            if ($sqlStatement === false)
            {
                throw new PDOException("Erreur lors de la préparation de la requête.");
            }

            // Exécuter la requête SQL en liant les variables au paramètres dans la requête SQL.
            $sqlStatement->execute([
                'email' => $administratorToCreate->email,
                'password' => password_hash($administratorToCreate->password, PASSWORD_DEFAULT)
            ]);

            // Retourner success = true.
            return true;
        }
        catch(PDOException $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner succès = false.
            return false;
        }
    }

    /**
     * Méthode qui supprime une liste d'administrateurs par leurs ids de la BD.
     * @author Antoine Ouellette
     * @throws PDOException Peut lancer des erreurs PDOException.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete_administrators_by_ids(array $administratorToDeleteIds): bool
    {
        try
        {
            // Générer les `?` dans la requête SQL selon le nombre d'administrateurs à supprimer.
            $filtersPlaceholders = implode(',', array_fill(0, count($administratorToDeleteIds), '?'));

            // Déclaration de la requête SQL.
            $sqlRequest = "DELETE FROM users WHERE role_id = 0 AND id IN ($filtersPlaceholders)";

            // Préparer la requête SQL.
            $sqlResponse = $this->db->prepare($sqlRequest);

            // Binder les valeurs du tableau avec les `?` et exécuter la requête.
            $sqlResponse->execute($administratorToDeleteIds); // Quand on passe un tableau à la méthode `execute()`, PDO va lier les items aux `?` dans la requête préparée.

            if ($sqlResponse === false) {
                throw new PDOException("Une erreur est survenue lors de la suppression des administrateurs.");
            }

            return true;
        }
        catch(PDOException $exception)
        {
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            return false;
        }
    }

	/**
	 * Fonction qui permet d'obtenir tous les juges et les séparer en blacklisted ou non.
	 * @author Thomas-Gabriel Paquin
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|false Retourne un tableau des juges blacklister, sinon retourne false.
	 */
    public function get_all_judges_blacklisted()
    {
		try
		{
			$sql = "SELECT users.id, first_name as firstName, last_name as lastName, email, uuid, categories.name AS category, blacklisted, activated
			FROM users 
			INNER JOIN judge ON judge.users_id = users.id
			INNER JOIN categories ON judge.categories_id = categories.id
			WHERE role_id = 1 AND blacklisted = 1";

			$req = $this->db->prepare($sql);
			$req->execute();
			return $req->fetchAll();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
    }
	

	/**
	 * Fonction qui permet d'obtenir tous les utilisateurs activer
	 * @author Alex Des Ruisseaux
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|false Retourne un tableau des utilisateurs, sinon retourne false.
	 */
    public function get_activated_users(): array
    {
		try
		{
			$sql = "SELECT first_name, last_name, email, role.name from users inner join role on role_id = role.id WHERE activated = 1";
			$req = $this->db->query($sql);
			return $req->fetchAll();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return [];
		}
    }

	/**
	 * Fonction qui permet de changer le role d'un utilisateur activer
	 * @author Alex Des Ruisseaux
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|false Retourne un tableau des utilisateurs, sinon retourne false.
	 */
    public function change_user_role(UserRole $userRole): int
    {
		try
		{
			$sql = "UPDATE users SET role_id=(SELECT id FROM role WHERE name=:role) WHERE email=:email";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"role" => $userRole->roleName,
				"email" => $userRole->email
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }

	/**
	 * Fonction qui permet d'obtenir un utilisateur par son id.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return mixed|false Retourne un tableau des données de l'utilisateur, sinon ça retourne false.
	 */
    public function get_user_by_id( int $id )
    {
		try
		{
			$sql = "SELECT id, first_name, last_name, username, pwd, email, picture, picture_consent, activated, blacklisted, role_id 
				FROM users
				WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $id
			));
			$response = $req->fetch();
			return !$response ? false : $response;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
    }

	/**
	 * Fonction qui permet d'obtenir un utilisateur par son id.
	 * @author Christopher Boisvert
	 * @param string $email Email de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return mixed|false Retourne un tableau des données de l'utilisateur, sinon ça retourne false.
	 */
	public function get_user_by_email( string $email )
	{
		try
		{
			$sql = "SELECT id, first_name, last_name, username, pwd, email, picture, picture_consent, activated, blacklisted, role_id 
				FROM users
				WHERE email=:email";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"email" => $email
			));
			$response = $req->fetch();
			return !$response ? false : $response;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Fonction qui permet d'obtenir un utilisateur par son email. 
	 * (sert seulement pour une fonction de juge dans add_judge_judge)
	 * @author Déreck "THE GOAT" Lachance
	 * @param string $email Email de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return int|false Retourne un tableau des données de l'utilisateur, sinon ça retourne false.
	 */
	public function get_judge_by_email( string $email )
	{
		try
		{
			$sql = "SELECT id
				FROM users
				WHERE email=:email AND role_id = 1";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"email" => $email
			));
			$response = $req->fetch();
			return !$response ? false : $response['id'];
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Fonction qui permet d'obtenir un juge par son user_id.
	 * @author Christopher Boisvert
	 * @param int $user_id Identifiant de l'utilisateur.
	 * @return array|false Retourne un tableau représentant le juge, sinon sa retourne false.
	 */
	public function get_judge_by_user_id(int $user_id)
	{
		try
		{
			$sql = "SELECT id, uuid, categories_id, users_id FROM judge WHERE users_id=:users_id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"users_id" => $user_id
			));
			$response = $req->fetch();
			return !$response ? false : $response;	
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Fonction qui permet d'ajouter un utilisateur.
	 * @author Tristan Lafontaine
	 * @param User $user Users à ajouter.
	 * @return int Retourne le nombre de lignes ajouté.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function add_user( User $user ): int
    {
		try
		{
			$sql = "INSERT INTO users(first_name, last_name, username, pwd, email, picture, picture_consent, reset_token, activation_token, activated, blacklisted, role_id) 
                VALUES(:first_name, :last_name, :username, :pwd, :email, :picture, :picture_consent, :reset_token, :activation_token, :activated, :blacklisted, :role_id)";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"first_name" => $user->first_name,
				"last_name" => $user->last_name,
				"username" => $user->username,
				"pwd" => $user->pwd,
				"email" => $user->email,
				"picture" => $user->picture_consent,
				"picture_consent" => $user->picture_consent,
				"reset_token" => $user->reset_token,
				"activation_token" => $user->activation_token,
				"activated" => $user->activated,
				"blacklisted" => $user->blacklisted,
				"role_id" => $user->role_id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			var_dump($e);
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }
	
	/**
	 * Fonction qui permet d'ajouter un juge dans user.
	 * @author Jean-Philippe Bourassa
	 * @param Judge $judge Users à ajouter.
	 * @return int Retourne le nombre de lignes ajouté.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function add_judge_user( Judge $judge ): int
    {
		try{
			$sql = "INSERT INTO users(first_Name, last_Name, email, picture_consent, activated, blacklisted, role_id) 
					VALUES(:firstName, :lastName, :email, :picture_consent, :activated, :blacklisted, :role_id)";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"firstName" => $judge->firstName,
				"lastName" => $judge->lastName,
				"email" => $judge->email,
				"picture_consent" => $judge->pictureConsent ? 1 : 0,
				"activated" => 1,
				"blacklisted" => 0,
				"role_id" => 1,
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			var_dump($e);
			$context["http_error_code"] = $e->getCode();
            if($this->logHandler != null)
			{
				$this->logHandler->critical($e->getMessage(), $context);
			}
			return 0;
		}
    }
	
	/**
	 * Fonction qui permet d'ajouter un user dans juge avec son user_id.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @param Judge $judge Judge à ajouter.
	 * @return string|Result Retourne le nombre de lignes ajouté.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function add_judge_judge(Judge $judge)
    {
		try
		{	
			if($judge->category == null){
			return new Result(EnumHttpCode::BAD_REQUEST, array("Une ereur est survenue lors de la récupération des catégories 1"));
			}
			$categoryArray= $this->get_category($judge->category);
            if(sizeOf($categoryArray) < 0){
                return new Result(EnumHttpCode::BAD_REQUEST, array("Une ereur est survenue lors de la récupération des catégories 2"));
            }
			
			$user_id = $this->get_judge_by_email($judge->email);
			if($user_id == false) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("Une ereur est survenue lors de la récupération des information utilisateur du juge."));
			}

			$sql = "INSERT INTO judge(categories_id, users_id, uuid) 
			VALUES(:category, :user, :uuid)";
			$req = $this->db->prepare($sql);
			$uuid = GeneratorUUID::generate_single_UUID();
			$req->execute(array(
				"category" => $categoryArray["id"],
				"user" => $user_id,
				"uuid" => $uuid,
			));
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout du juge(2)."));
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une ereur est survenue lors de l'ajout d'un juge."));
		}
    }

	/**
     * get_category
     * Permet d'obtenir l'id et le survey_id d'une categorie
     * @param  string $category Le nom de la catégorie
     * @return array
     */
    public function get_category(string $category) : array
    {
        try{
            $sql = "SELECT id,survey_id FROM categories WHERE name = :category";
            $req = $this->db->prepare($sql);
            $req->execute(array(
                "category" => $category
            ));
            $response = $req->fetch();
			return !$response ? [] : $response;
        }
        catch(PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return [];
        }
        
    }

	/**
	 * Fonction qui permet de modifier un utilisateur.
	 * @author Christopher Boisvert
	 * @param User $user User à modifié.
	 * @return int Retourne le nombre de lignes modifié.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function modify_user( User $user ): int
    {
		try
		{
			$sql = "UPDATE users SET first_name=:first_name, last_name=:last_name, username=:username, pwd=:pwd, email=:email, 
                picture=:picture, picture_consent=:picture_consent, activated=:activated, blacklisted=:blacklisted, role_id=:role_id) 
                WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"first_name" => $user->first_name,
				"last_name" => $user->last_name,
				"username" => $user->username,
				"pwd" => $user->pwd,
				"email" => $user->email,
				"picture" => $user->picture,
				"picture_consent" => $user->picture_consent,
				"activated" => $user->activated,
				"blacklisted" => $user->blacklisted,
				"role_id" => $user->role_id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }

	/**
	 * Fonction qui permet de modifier la catégorie un juge.
	 * @author Thomas-Gabriel Paquin
	 * @param int $userId ID de l'utilisateur a modifier.
	 * @param int $category ID de la catégorie du juge.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
	private function update_judge_category(int $userId, int $category)
	{
		$sql = "UPDATE judge SET categories_id=:categories_id
		WHERE users_id=:id";
		$query = $this->db->prepare($sql);
		$query->execute(array(
			":id" => $userId,
			":categories_id" => $category,
		));
		return $query->rowCount();
	}

	/**
	 * Fonction qui permet de modifier un juge.
	 * @author Thomas-Gabriel Paquin
	 * @param array $judge Juge à modifié.
	 * @return int Retourne le nombre de lignes modifié.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function update_judge_info( array $data ): int
    {
		try
		{
			$sql = "UPDATE users SET first_name=:first_name, last_name=:last_name ,email=:email, activated=:activated, blacklisted=:blacklisted
                	WHERE users.id=:id AND role_id = 1";
			$query = $this->db->prepare($sql);
			$query->execute(array(
				":id" => $data['judge']['id'],
				":first_name" => $data['judge']['firstName'],
				":last_name" => $data['judge']['lastName'],
				":email" => $data['judge']['email'],
				":activated" => $data['judge']['activated'],
				":blacklisted" => $data['judge']['blacklisted']
			));
			$results = $query->rowCount();

        	return $results || $this->update_judge_category($data['judge']['id'], $data['judge']['categoryId']);
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }

	/**
	 * Fonction qui permet d'activer un utilisateur.
	 * @author Christopher Boisvert
	 * @param int $id ID de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return int Retourne le nombre de ligne modifié.
	 */
	public function activate_user( int $id )
	{
		try
		{
			$sql = "UPDATE users SET activated=:activated WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"activated" => 1,
				"id" => $id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
	}

	/**
	 * Fonction qui permet de modifier un juge et ainsi finir son inscription.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @param Judge $judge Judge à modifié.
	 * @return int Retourne le nombre de lignes modifié.
	 *@throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function activate_judge( Judge $judge ): int
    {
		if($judge->user_id == null){
			$context["http_error_code"] = 500;
            $this->logHandler->critical("Couldn't activate the judge '".$judge->firstName." ".$judge->lastName."' for not having a user_id.", $context);
			return 0;
		}
		try
		{
			$sql = "UPDATE users SET pwd=:pwd, picture_consent=:picture_consent, activated=:activated, activation_token=:activation_token 
			WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"pwd" => $judge->pwd,
				"picture_consent" => $judge->pictureConsent ? 1 : 0,
				"activated" => 1,
				"activation_token" => NULL,
				"id" => $judge->user_id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }

	/**
	 * Fonction qui permet de donner un activation_token à un utilisateur
	 * @author Jean-Philippe Bourassa
	 * @param int $id id de l'utilisateur
	 * @return int Retourne le nombre de lignes modifié.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 */
	public function set_activation_token(int $id): int
	{
		try
		{
			$sql = "UPDATE users SET activation_token=:activation_token) 
			WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"activation_token" => GeneratorUUID::generate_single_UUID(),
				"id" => $id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
	}

	/**
	 * Cherche l'usager via son token d'activation d'adresse courriel
	 * @author Mathieu Sévégny
	 * @param string $token Token d'action d'adresse courriel
	 * @return mixed L'usager trouvé (Peut-être null)
	 */
	public function get_user_by_activation_token(string $token)
	{
		try
		{
			$sql = "SELECT id, first_name, last_name, username, pwd, email, picture, picture_consent, activated, blacklisted, role_id
					FROM users WHERE activation_token=:activation_token;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"activation_token" => $token
			));
			$response = $req->fetch();
			return !$response ? false : $response;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Activation de l'adresse courriel de l'utilisateur via son identifiant
	 * @author Mathieu Sévégny
	 * @param int $userID Identifiant de l'utilisateur.
	 * @return bool Si ça a fonctionnée.
	 */
	public function activate_email_by_id(int $userID)
	{
		try
		{
			$sql = "UPDATE users SET activation_token=NULL, activated=1 WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $userID
			));

			return $req->rowCount() === 0;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}
	/**
	 * Cherche l'identifiant de l'équipe avec l'identifiant de l'usager
	 * @author Mathieu Sévégny
	 * @param int $userID Identifiant de l'utilisateur.
	 * @return int Identifiant de l'équipe, si pas trouvé, retourne -1
	 */
	public function get_team_id_by_user_id(int $userID)
	{
		try
		{
			$sql = "SELECT teams.id FROM teams
					INNER JOIN users_teams on users_teams.teams_id = teams.id
					WHERE users_teams.users_id = :id and activated = 0 ORDER BY id DESC;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $userID
			));
			if ($req->rowCount() == 1){
				return $req->fetch()["id"];
			}
			return -1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return -1;
		}
	}
	/**
	 * Cherche les usagers de l'équipe n'ayant pas leur courriel validé.
	 * @author Mathieu Sévégny
	 * @param int $teamID Identifiant de l'équipe.
	 * @return mixed Usagers n'ayant pas leur courriel validé
	 */
	public function is_all_team_email_activated(int $teamID)
	{
		try
		{
			$sql = "SELECT users.id from users_teams
					INNER JOIN users on users.id = users_teams.users_id
					WHERE users_teams.teams_id = :id AND users.activation_token IS NOT NULL;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $teamID
			));
			return $req->rowCount() === 0;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}
	/**
	 * Active l'équipe.
	 * @author Mathieu Sévégny
	 * @param int $teamID Identifiant de l'équipe.
	 * @return bool Requête a fonctionné?
	 */
	public function activate_team(int $teamID)
	{
		try
		{
			$sql = "UPDATE teams SET activated = 1 where teams.id = :id;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $teamID
			));
			return $req->rowCount() === 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Fonction qui permet de supprimer un utilisateur.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return int Retourne le nombre de ligne supprimé.
	 */
    public function delete_judge( int $users_id ): int
    {
		try
		{
			$sql = "DELETE FROM judge WHERE users_id=:users_id;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"users_id" => $users_id
			));
			if($req->rowCount()!=1){
				$context["http_error_code"] = 500;
				if($this->logHandler != null){
					$this->logHandler->critical("'".$req->rowCount()."' judge(s) deleted with user_id:".$users_id, $context);
				}
				return $req->rowCount();
			}
			$sql = "DELETE FROM users WHERE id=:id;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $users_id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
			if($this->logHandler != null){
				$this->logHandler->critical($e->getMessage(), $context);
			}
			return 0;
		}
    }

	/**
	 * Fonction qui permet de supprimer un utilisateur.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return int Retourne le nombre de ligne supprimé.
	 */
    public function delete_user( int $id ): int
    {
		try
		{
			$id = htmlspecialchars($id);
			$sql = "DELETE FROM users WHERE id=:id";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"id" => $id
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
			if($this->logHandler != null){
				$this->logHandler->critical($e->getMessage(), $context);
			}
			return 0;
		}
    }
	
	/**
	 * Fonction qui permet d'obtenir les rôles
	 * @author Tristan Lafontaine
	 * @return array Retourne un tableau de rôle
	 */
	public function get_all_roles() : array
	{
		$sql = "SELECT name as role FROM role";
		$req = $this->db->prepare($sql);
		$req->execute();
		
		return $req->fetchAll();
	}

	/**
	 * Cherche les usagers de l'équipe n'ayant pas leur courriel validé.
	 * @author Maxime Demers Boucher
	 * @param int $teamID Identifiant de l'équipe.
	 * @return mixed Usagers n'ayant pas leur courriel validé
	 */
	public function email_adminValide(string $email)
	{
		try
		{
			$sql = "SELECT email,role_id from users
					WHERE email = :email AND role_id = 0;";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"email" => $email
			));
			return $req->rowCount() === 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}
}