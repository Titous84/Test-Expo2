<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Enums\EnumLengths;
use App\Fabricators\Emails\EmailJudgeFabricator;
use App\Fabricators\Emails\EmailAdminFabricator;
use App\Fabricators\Emails\EmailMotDePasseOublierFabricator;
use App\Handlers\LogHandler;
use App\Models\Result;
use App\Models\User;
use App\Models\Judge;
use App\Models\UserRole;
use App\Models\Administrators\AdministratorToCreate;
use App\Services\EmailService;
use App\Services\TwigService;
use App\Services\VerificationCodeService;
use App\Repositories\TokenRepository;
use App\Repositories\UserRepository;
use App\Validators\ValidatorUserRole;
use App\Validators\ValidatorUser;
use App\Validators\ValidatorAdministrator;
use App\Validators\ValidatorJudge;
use App\Utils\GeneratorUUID;
use Exception;
use PDOException;


use function DI\get;

/**
 * Classe UserService.
 * @author Christopher Boisvert
 * @author Tristan Lafontaine
 * @author Alex Des Ruisseaux
 * @package App\Services
 */
class UserService
{
	/**
	 * @var UserRepository Dépôt lié à la bdd permettant d'accéder aux utilisateurs.
	 */
	private $userRepository;

	/**
	 * @var ValidatorUserRole Validateur permettant de vérifier les données liés à un rôle.
	 */
	private $validatorUserRole;

	/**
	 * @var ValidatorUser Validateur qui permet de vérifier un utilisateur.
	 */
	private $validatorUser;

    /**
     * @var ValidatorAdministrator Validateur qui permet de vérifier un administrateur.
     */
    private $validatorAdministrator;

	/**
	 * @var ValidatorJudge Validateur qui permet de vérifier un judge.
	 */
	private $validatorJudge;

	/**
	 * @var LogHandler Gestionnaire de log.
	 */
	private $logHandler;

	/**
	 * emailService
	 *
	 * @var EmailService Permet d'avoir access à la classe EmailService'
	 */
	private $emailService;

	/**
	 * twigService
	 *
	 * @var TwigService Permet d'avoir access à la classe TwigService'
	 */
	private $twigService;

	/**
	 * VerificationService
	 *
	 * @var VerificationCodeService Permet d'avoir access à la classe VerificationCodeService'
	 */
	private $verificationCodeService;

	/**
	 * UserService constructeur.
	 * @param UserRepository $userRepository Dépôt des utilisateurs.
	 * @param TokenRepository $tokenRepository Dépôt des tokens.
	 * @param LogHandler $logHandler Permet d'enregistrer les erreurs.
	 */
	public function __construct(UserRepository $userRepository, ValidatorUserRole $validatorUserRole, LogHandler $logHandler, ValidatorUser $validatorUser, ValidatorAdministrator $validatorAdministrator, ValidatorJudge $validatorJudge, EmailService $emailService, TwigService $twigService, VerificationCodeService $verificationCodeService)
	{
		$this->userRepository = $userRepository;
		$this->validatorUserRole = $validatorUserRole;
		$this->logHandler = $logHandler;
		$this->validatorUser = $validatorUser;
        $this->validatorAdministrator = $validatorAdministrator;
		$this->validatorJudge = $validatorJudge;
		$this->emailService = $emailService;
		$this->twigService = $twigService;
		$this->verificationCodeService = $verificationCodeService;
	}

	/**
	 * Fonction permettant d'obtenir les utilisateurs.
	 * @author Christopher Boisvert
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_all_users(): Result
	{
		try {
			$resultGetAllUsers = $this->userRepository->get_all_users();

			if ($resultGetAllUsers == null) {
				return new Result(EnumHttpCode::NOT_FOUND, array("Aucun utilisateur n'a été trouvé !"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("Les utilisateurs ont été trouvés !"), $resultGetAllUsers);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des utilisateurs."));
		}
	}

	/**
	 * Fonction permettant d'obtenir les tout juges actifs ou les juges blacklister selon la page qui est ouvert.
	 * @author Thomas-Gabriel Paquin
	 * @param int $blacklisted information si le judge fait partis de la liste noire ou non.
	 * @return Result Retourne un tableau de tout les juges actifs.
	 */
	public function get_all_judges(int $blacklisted): Result
    {
	    try
	    {
			if($blacklisted == 1){
				$resultGetAllJudges = $this->userRepository->get_all_judges_blacklisted();
				
			}else{
				$resultGetAllJudges = $this->userRepository->get_all_judges();
			}
			
		    if($resultGetAllJudges == false)
		    {
			    return new Result(EnumHttpCode::NOT_FOUND, array("Aucun juge n'a été trouvé !"), []);
		    }

		    return new Result( EnumHttpCode::SUCCESS, array("Les juges ont été trouvés !"), $resultGetAllJudges);
	    }
	    catch (Exception $e)
	    {
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
		    return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des juges."));
	    }
    }

	/**
     * Fonction qui permet de mettre à jour les informations d'un juge
	 * @author Thomas-Gabriel Paquin
     * @param  array $judge
     * @return Result Retourne le résultat de l'opération
     */

	public function update_judge_infos(array $judge) : Result
	{
		try {
			$judge["judge"]["category"] = strval($judge["judge"]["categoryId"]);
			$resultValidationJudgeInfo = $this->validatorJudge->validate($judge["judge"]);

			if ($resultValidationJudgeInfo->get_http_code() != EnumHttpCode::SUCCESS)
				return $resultValidationJudgeInfo;

			if ($this->userRepository->update_judge_info($judge)) {
				return new Result(EnumHttpCode::SUCCESS, array("Les informations du juge ont été mise à jour avec succès."), true);
			}else{
				return new Result(EnumHttpCode::BAD_REQUEST, array("Les informations du juge n'ont pas pu être mise à jour."));
			}
		}catch (PDOException $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
		}catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
	}

    /**
     * Récupère tous les administrateurs.
     * @author Antoine Ouellette
     * @return Result Un objet contenant les informations à mettre dans le corps de la réponse.
     */
    public function get_all_administrators(): Result
    {
        try
        {
            // Le repository retourne un tableau de tous les administrateurs.
            $allAdministratorsArray = $this->userRepository->get_all_administrators();

            // Retourne le tableau de tous les administrateurs et le code HTTP 200.
            return new Result( EnumHttpCode::SUCCESS, array("Les administrateurs ont été trouvés avec succès."), $allAdministratorsArray);
        }
        catch (Exception $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner une erreur 500 et un message d'erreur.
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des administrateurs."));
        }
    }

    /**
     * Crée un administrateur.
     * @author Antoine Ouellette
     * @param array|object|null $requestBody Le corps de la requête contenant les informations de l'administrateur à créer.
     * @return Result Un objet contenant les informations à mettre dans le corps de la réponse.
     */
    public function create_administrator(array|object|null $requestBody): Result
    {
        try
        {
            // Vérifie si le corps de la requête est valide.
            $validationSuccess = $this->validatorAdministrator->validate($requestBody);
            // Si la validation ne retourne pas un succès, récupérer le message d'erreur dans le résultat.
			if ($validationSuccess->get_http_code() != EnumHttpCode::SUCCESS) {
				return $validationSuccess; // Retourner la réponse au client.
			}

            // Vérifie si un administrateur avec cet email existe déjà.
            if ($this->userRepository->is_email_already_in_use($requestBody["email"]))
            {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Un administrateur avec cet email existe déjà."));
            }

            // Instancie un nouvel administrateur à partir du corps de la requête.
            $administratorToCreate = new AdministratorToCreate($requestBody);

            // Le repository ajoute l'administrateur dans la BD.
            if (!$this->userRepository->create_administrator($administratorToCreate))
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la création de l'administrateur."));
            }

            // Retourne un message de succès et le code HTTP 201.
            return new Result(EnumHttpCode::CREATED, array("L'administrateur a été créé avec succès."), true);
        }
        catch (Exception $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner une erreur 500 et un message d'erreur.
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la création de l'administrateur."));
        }
    }

    /**
     * Supprime une liste d'administrateurs par leurs ids.
     * 
     * Cette méthode retourne un code HTTP 200 si AU MOINS UN des ids d'administrateurs existe dans la BD.
     * Sinon, si TOUS les ids d'administrateurs n'existent pas dans la BD, un code HTTP 404 est retourné.
     * 
     * @author Antoine Ouellette
     * @return Result Un objet contenant les informations à mettre dans le corps de la réponse.
     */
    public function delete_administrators_by_ids(array $administratorsToDeleteIds): Result
    {
        try
        {
            // Vérifie si tous les ids ne sont pas trouvés (n'existent pas dans la BD).
            if ($this->are_all_administrators_ids_not_found($administratorsToDeleteIds))
            {
                // Retourne une erreur 404 (erreur dans la requête du client).
                return new Result(EnumHttpCode::NOT_FOUND, array("Les ids fournis n'existent pas dans la BD."));
            }
            // (Sinon, si certains ids sont valides, le code HTTP 200 sera retourné.)

            // Le repository retourne le succès de la suppression.
            $deleteSuccess = $this->userRepository->delete_administrators_by_ids($administratorsToDeleteIds);

            // Retourne un message de succès et le code HTTP 200.
            return new Result(EnumHttpCode::SUCCESS, array("Les administrateurs ont été supprimés avec succès."), $deleteSuccess);
        }
        catch (Exception $exception)
        {
            // Si une erreur survient.

            // Logguer l'erreur.
            $context["http_error_code"] = $exception->getCode();
            $this->logHandler->critical($exception->getMessage(), $context);
            // Retourner une erreur 500 et un message d'erreur.
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la suppression des administrateurs."));
        }
    }

    /**
     * Vérifie si TOUS les administrateurs d'un tableau ne sont pas trouvés (n'existent TOUS pas dans la BD).
     * @param array $administratorsToDeleteIds La liste des ids d'administrateurs à vérifier.
     * @return bool true si TOUS les ids ne sont pas trouvés, false si AU MOINS UN id est trouvé.
     */
    private function are_all_administrators_ids_not_found(array $administratorsToDeleteIds): bool
    {
        foreach ($administratorsToDeleteIds as $currentId)
        {
            // Si un des ids est trouvé (existant).
            if ($this->userRepository->get_administrator_by_id($currentId) !== null)
            {
                return false; // AU MOINS UN id est valide, donc ils ne sont pas tous invalides.
            }
        }
        return true; // Aucun id n'est valide, donc ils sont TOUS invalides.
    }

	/**
	 * Fonction permettant d'obtenir les utilisateurs activés.
	 * @author Alex Des Ruisseaux
	 * @author Christopher Boisvert
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_activated_users(): Result
	{
		try {
			$resultGetActivatedUsers = $this->userRepository->get_activated_users();

			if ($resultGetActivatedUsers === null) {
				return new Result(EnumHttpCode::NOT_FOUND, array("Aucun utilisateur n'a été trouvé !"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("Les utilisateurs ont été trouvés !"), $resultGetActivatedUsers);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des utilisateurs."));
		}
	}

	/**
	 * Fonction qui permet de modifier le role d'un utilisateur.
	 * @author Alex Des Ruisseaux
	 * @author Christopher Boisvert
	 * @param mixed $userRoleJSON Tableau de données du rôle à changer à l'utilisateur.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function modify_user_role($userRoleJSON): Result
	{
		try {
			$resultValidationUserRole = $this->validatorUserRole->validate($userRoleJSON);

			if ($resultValidationUserRole->get_http_code() != EnumHttpCode::SUCCESS)
				return $resultValidationUserRole;

			$userRole = new UserRole($userRoleJSON);

			$resultChangedUsers = $this->userRepository->change_user_role($userRole);

			if ($resultChangedUsers === 0) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas été modifié"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("L'utilisateur a été modifié !"));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
	}

	/**
	 * Fonction qui permet d'obtenir un utilisateur par son id.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_user_by_id(int $id): Result
	{
		try {
			if ($id <= 0) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("L'identifiant se doit d'être un chiffre positif."));
			}

			$resultGetUserById = $this->userRepository->get_user_by_id($id);

			if ($resultGetUserById == null) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas été trouvé !"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("L'utilisateur a été trouvé !"), $resultGetUserById);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention de l'utilisateur."));
		}
	}

	/**
	 * Fonction qui permet d'obtenir un utilisateur par son activation token.
	 * @author Jean-Philippe Bourassa
	 * @param string $token Activation token de l'utilisateur.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_user_by_activation_token(string $token): Result
	{
		try {
			if (strlen($token) != EnumLengths::UUID) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("Le jeton se doit d'être présent."));
			}

			$user = $this->userRepository->get_user_by_activation_token($token);

			if (!$user) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas été trouvé ou l'adresse courriel a déjà été validée !"));
			}

			$resultGetUserByActivationToken = $this->userRepository->get_user_by_activation_token($token);

			if ($resultGetUserByActivationToken === null) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas été trouvé !"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("L'utilisateur a été trouvé !"), $resultGetUserByActivationToken);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention de l'utilisateur."));
		}
	}

	/**
	 * Fonction qui permet d'ajouter un utilisateur.
	 * @author Tristan Lafontaine
	 * modifier par Maxime Demers Boucher
	 * @param array $userJSON Tableau représentant l'utilisateur.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function add_user(array $userJSON): Result
	{
		try {
			//On vérifie les données reçues par l'utilisateur
			$userResult = $this->validatorUser->validate($userJSON);

			//Si des erreurs surviennent, on les affiche.
			if ($userResult->get_http_code() != EnumHttpCode::SUCCESS) {
				return $userResult;
			}

			//On créer le nouvel objet utilisateur.
			$user = new User($userJSON);

			//On tente de vérifier si l'utilisateur est déjà là.
			$userAlreadyExist = $this->userRepository->get_user_by_email($user->email);

			//Si l'utilisateur existe déjà, on affiche une erreur.
			if ($userAlreadyExist != null) {
				return new Result(
					EnumHttpCode::BAD_REQUEST,
					array("L’adresse courriel que vous avez fournie est déjà utilisée.")
				);
			}

			//On essaie d'ajouter l'utilisateur dans la bdd.
			$resultAddUser = $this->userRepository->add_user($user);

			//Si une ligne est négative est zéro, une erreur est survenue.
			if ($resultAddUser == 0) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout de l'utilisateur."));
			}

			//On va aller chercher les données de l'utilisateur avec son id.
			$userData = $this->userRepository->get_user_by_email($user->email);

			//Si c'est vide, ca veut dire qu'une erreur est survenue.
			if ($userData == null) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout de l'utilisateur."));
			}

			//On créer un nouvel objet avec les données reçues.
			$user_with_id = new User($userData);

			//On retourne une résultat de succès de l'ajout de l'utilisateur.
			if ($user->role_id == 0) {
				$emailEnvoyer = $this->send_email_admin($user_with_id);
				if ($emailEnvoyer != null) {
					return $emailEnvoyer;
				}
			} else {
				$emailEnvoyer = $this->send_email_judges($user_with_id);
				if ($emailEnvoyer != null) {
					return $emailEnvoyer;
				}
			}
			//On retourne une résultat de succès de l'ajout de l'utilisateur.
			return new Result(EnumHttpCode::SUCCESS, array("L'utilisateur a été ajouté !"), $user_with_id);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
	}

	/**
	 * Fonction qui permet d'ajouter un juge.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @param array $judgeJSON Tableau représentant le juge.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function add_judge_user(array $judgeJSON): Result
	{
		try {
			//On vérifie les données reçues par l'utilisateur
			$judgeResult = $this->validatorJudge->validate($judgeJSON);

			if ($judgeResult->get_http_code() !== 200) {
				return $judgeResult;
			}
			//On créer le nouvel objet juge.
			$judge = new Judge($judgeJSON);


			//On tente de vérifier si le juge est déjà là.
			$judgeAlreadyExist = $this->userRepository->get_user_by_email($judge->email);

			//Si le juge existe déjà, on affiche une erreur.
			if ($judgeAlreadyExist !== null && $judgeAlreadyExist !== false) {
				return new Result(
					EnumHttpCode::BAD_REQUEST,
					array("L’adresse courriel que vous avez fournie est déjà utilisée.")
				);
			}

			$token = GeneratorUUID::generate_single_UUID();

			//On essaie d'ajouter le juge dans la bd.
			$resultAddUser = $this->userRepository->add_judge_user($judge);

			//Si une ligne est négative est zéro, une erreur est survenue.
			if ($resultAddUser == 0) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout du juge(1)."));
			}

			$resultAddJudge = $this->userRepository->add_judge_judge($judge);

			if (gettype($resultAddJudge) == 200) {
				return $resultAddJudge;
			}

			$judgeActive = $this->userRepository->activate_judge($judge);
			$messageErreur = [];

            //Vérification si l'équipe est activé
			if (!is_array($judgeActive)) {
				$judgeActive = [];
			}
			for ($t = 0; $t < sizeof($judgeActive); $t++) {
                if($judgeActive[$t] != false){
                    $messageErreur[] = $judgeActive[$t]["first_name"] . " " . $judgeActive[$t]["last_name"] . ", vous devez activer votre compte avant de créer un nouveau juge.";
                }
            }
            //Retourne une erreur si un membre de l'équipe n'a pas activé son autre équipe
            if(sizeOf($messageErreur) != 0){
                return new Result(EnumHttpCode::BAD_REQUEST, array("L'équipe n'est pas activé"), $messageErreur);
            }

			//On retourne une résultat de succès de l'ajout du juge.
			return new Result(EnumHttpCode::CREATED, array("Le juge a été ajouté avec succès."), array("Le juge a été ajouté avec succès."));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'inscription du juge."));
		}
	}

	/**
	 * Fonction qui permet de compléter l'inscription d'un juge.
	 * @author Jean-Philippe Bourassa
	 * @param array $judgeJSON Tableau représentant le juge.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function add_judge_judge(array $judgeJSON): Result
	{
		try {
			//On vérifie les données reçues par l'utilisateur
			$judgeResult = $this->validatorJudge->validate($judgeJSON);

			//Si des erreurs surviennent, on les affiche.
			if (count($judgeResult->get_message()) > 0) {
				return $judgeResult;
			}

			//On créer le nouvel objet juge.
			$judge = new Judge($judgeJSON);

			$judgeAlreadyExist = $this->userRepository->get_user_by_email($judge->email);

			//Si le juge n'existe pas, on affiche une erreur.
			if (is_null($judgeAlreadyExist)) {
				return new Result(
					EnumHttpCode::BAD_REQUEST,
					array("Le juge n'a pas encore été créé.")
				);
			}

			//On essaie d'ajouter le dans la bd.
			$resultAddJudge = $this->userRepository->add_judge_judge($judge);

			if (gettype($resultAddJudge) == "object") {
				return $resultAddJudge;
			}

			//On va aller chercher les données de l'utilisateur avec son activation token.
			$judgeData = $this->userRepository->get_judge_by_user_id($judge->user_id);

			//Si c'est vide, ca veut dire qu'une erreur est survenue.
			if ($judgeData == null) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout du juge."));
			}

			$judgeJudge = $this->userRepository->activate_judge($judge);

			//Si c'est vide, ca veut dire qu'une erreur est survenue.
			if ($judgeJudge == 0) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout du juge."));
			}

			//On retourne une résultat de succès de l'ajout de l'utilisateur.
			return new Result(EnumHttpCode::SUCCESS, array("Le juge a été ajouté !"), $judgeData);
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'inscription du juge."));
		}
	}

	/**
	 * Fonction qui permet de modifier un utilisateur.
	 * @author Christopher Boisvert
	 * @param $userJSON array Information lié à l'utilisateur.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function modify_user(array $userJSON): Result
	{
		try {
			//On vérifie les données reçues par l'utilisateur
			$userResult = $this->validatorUser->validate($userJSON);

			//Si des erreurs surviennent, on les affiche.
			if ($userResult->get_http_code() != EnumHttpCode::SUCCESS) {
				return $userResult;
			}

			//On créer le nouvel objet utilisateur.
			$user = new User($userJSON);

			//TODO: COMPLÉTER LA MODIFICATION DES UTILISATEURS

			return new Result(EnumHttpCode::SERVER_ERROR, array("Cette fonction n'est pas encore implémenté..."));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
	}

	/***
	 * Fonction qui permet d'activer un utilisateur selon son id.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur à supprimer.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function activate_user(int $id): Result
	{
		try {
			if ($id <= 0) {
				return new Result(500, array("L'identifiant ne peut être négatif ou à zéro."));
			}

			$resultActivationUser = $this->userRepository->activate_user($id);

			if ($resultActivationUser == 0) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas pu être modifié."));
			}

			return new Result(EnumHttpCode::SUCCESS, array("L'activation de l'utilisateur a fonctionné."));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue sur le serveur."));
		}
	}

	/***
	 * Fonction qui permet de supprimer un juge selon son id.
	 * @author Étienne Nadeau
	 * @param int $id  du juge à supprimer.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function delete_judge(int $user_id): Result
	{
		try {
			
			if ($user_id <= 0) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("L'identifiant se doit d'être un chiffre positif."));
			}
				$resultDeleteUser = $this->userRepository->delete_judge($user_id);
			
			
			
			if (!$resultDeleteUser) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("le juge dont l'id est " . $user_id . " n'a pas été supprimé"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("Le juge a été supprimé avec succès."));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue sur le serveur."));
		}
	}

	/***
	 * Fonction qui permet de supprimer un utilisateur selon son id.
	 * @author Christopher Boisvert
	 * @param int $id Credential de l'utilisateur à supprimer.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
	public function delete_user(int $id): Result
	{
		try {
			$resultDeleteUser = $this->userRepository->delete_user($id);

			if (!$resultDeleteUser) {
				return new Result(EnumHttpCode::SERVER_ERROR, array("L'utilisateur n'a pas été bel et bien été supprimé"));
			}

			return new Result(EnumHttpCode::SUCCESS, array("Le utilisateur a été supprimé avec succès."));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue sur le serveur."));
		}
	}
	/**
	 * Fonction qui permet d'activer le courriel d'un usager par un token.
	 * @author Tristan Lafontaine
	 * @param string $token Credential de l'utilisateur.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function activate_email_by_token(string $token): Result
	{
		try {
			if (strlen($token) != EnumLengths::UUID) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("Le jeton se doit d'être présent."));
			}

			//Avoir l'usager
			$user = $this->userRepository->get_user_by_activation_token($token);

			if (!$user) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'utilisateur n'a pas été trouvé ou l'adresse courriel a déjà été validée !"));
			}

			//Active le user
			$this->userRepository->activate_email_by_id($user["id"]);


			//Trouve l'équipe à partir du user
			$teamID = $this->userRepository->get_team_id_by_user_id($user["id"]);

			if ($teamID == -1) {
				return new Result(EnumHttpCode::NOT_FOUND, array("L'équipe relié à l'usager n'a pas été trouvée !"));
			}
			//Regarde tous les usagers avec un champ de validation de courriel non vide de l'équipe
			if ($this->userRepository->is_all_team_email_activated($teamID)) {
				//Si il n'en reste plus -> active l'équipe
				if (!$this->userRepository->activate_team($teamID)) {
					return new Result(EnumHttpCode::SERVER_ERROR, array("L'adresse courriel a été activée, mais l'activation de l'équipe a échoué!"));
				}
			}

			return new Result(EnumHttpCode::SUCCESS, array("L'adresse courriel a été validée !"));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention de l'utilisateur."));
		}
	}
	/**
	 * Fonction qui permet d'envoyer le courriel d'inscription aux juges présents dans la bd.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function send_email_judges(): Result
	{
		try {
			$judges = $this->userRepository->get_all_judges();
			foreach ($judges as $judge) {
				$validateEmail = new EmailJudgeFabricator($this->emailService, $this->twigService);
				$verificationEmail = $validateEmail->send_mail($judge["email"], $judge["first_name"], $judge["last_name"], $judge["uuid"]);
				//Vérifie que le courriel est bien envoyé.
				if ($verificationEmail->get_http_code() != 200) {
					return new Result(EnumHttpCode::BAD_REQUEST, array("Échec de l'envoi"), array("Il a eu un problème lors de l'envoi de l'email d'inscription"));
				}
			}
			return new Result(EnumHttpCode::SUCCESS, array("YEAH!"));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'envoi des courriels."));
		}
	}

	/**
	 * Fonction qui permet d'envoyer le courriel du mot de passe
	 * Fortement inspiré de send_email_judge
	 * @author Maxime Demers Boucher
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function send_email_admin(User $admin): Result
	{
		try {
			$validateEmail = new EmailAdminFabricator($this->emailService, $this->twigService);
			$verificationEmail = $validateEmail->send_mail($admin->email, $admin->first_name, $admin->last_name, $admin->username);
			//Vérifie que le courriel est bien envoyé.
			if ($verificationEmail->get_http_code() != 200) {
				return new Result(EnumHttpCode::BAD_REQUEST, array("Échec de l'envoi"), array("Il a eu un problème lors de l'envoi de l'email"));
			}
			return new Result(EnumHttpCode::SUCCESS, array("Un courriel à été envoyé"), array("L'administrateur à été créer avec succès"));
		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::BAD_REQUEST, array("Échec de l'envoi"), array("Il a eu un problème lors de l'envoi de l'email"));
		}
	}

	/**
	 * Fonction qui permet d'envoyer le courriel du mot de passe
	 * Fortement inspiré de send_email_judge
	 * @author Maxime Demers Boucher
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function send_email_PWF(string $email, string $code): Result
	{
		try {
			$emailAdminValide = $this->userRepository->email_adminValide($email);
			if ($emailAdminValide == true) {
				$validateEmail = new EmailMotDePasseOublierFabricator($this->emailService, $this->twigService);
				//Vérifie que le courriel est bien envoyé.
				$verificationEmail = $validateEmail->send_mail($email, $code);
				return new Result(EnumHttpCode::SUCCESS, array("Le courriel a été envoyé"), $verificationEmail);
			} else {
				$this->verificationCodeService->delete_code($email);
				return new Result(EnumHttpCode::BAD_REQUEST, array("Le courriel n'est pas associer a un admin"));
			}

		} catch (Exception $e) {
			$context["http_error_code"] = $e->getCode();
			$this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'envoi du courriels."));
		}
	}
	/**
	 *Fonction qui permet d'obtenir les rôles
	 * @author Tristan Lafontaine
	 * @return Result Retourne une réponse
	 */
	public function get_all_roles(): Result
	{
		try {

			$response = $this->userRepository->get_all_roles();

			if (count($response) === 0) {
				return new Result(EnumHttpCode::SUCCESS, array('Il n\'a pas de rôle'));
			}

			return new Result(EnumHttpCode::SUCCESS, array('Success'), $response);
		} catch (PDOException $e) {
			return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
		} catch (Exception $exception) {
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
	}
}
