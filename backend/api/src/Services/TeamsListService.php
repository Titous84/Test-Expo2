<?php

namespace App\Services;

use App\Handlers\LogHandler;
use App\Repositories\TeamsListRepository;
use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Models\TeamInfo;
use App\Models\TeamMember;
use PDOException;
use App\Validators\ValidatorsTeamsInfos;
use App\Validators\ValidatorTeamsMembers;
use App\Repositories\SignUpTeamRepository;
use Exception;

/**
 * Class TeamsListService
 * @package App\Services
 * @author Tristan Lafontaine, Carlos Cordeiro
 */
class TeamsListService
{

    /**
     * @var TeamsListRepository Dépôt liée à la bd permettant de faire les requêtes
     */
    private $teamsListRepository;

    /**
     * @var ValidatorsTeamsInfos Validation des champs de l'équipe
     */
    private $validatorsTeamsInfos;

    /**
     * @var ValidatorTeamsMembers Validation des champs des membres de l'équipe
     */
    private $validatorsTeamsMembers;

    /**
     * @var SignUpTeamRepository Dépôt liée à la bd permettant de faire les requêtes
     */
    private $signUpTeamRepository;

    /**
     * @var LogHandler Gestionnaire de log.
     */
    private $logHandler;

    /**
     * TeamsListService constructeur
     *
     * @param  TeamsListRepository $teamsListRepository
     * @param  ValidatorsTeamsInfos $validatorsTeamsInfos
     * @param  ValidatorTeamsMembers $validatorsTeamsMembers
     * @param  SignUpTeamRepository $signUpTeamRepository
     */
    public function __construct(TeamsListRepository $teamsListRepository, ValidatorsTeamsInfos $validatorsTeamsInfos, ValidatorTeamsMembers $validatorsTeamsMembers, SignUpTeamRepository $signUpTeamRepository, LogHandler $logHandler)
    {
        $this->teamsListRepository = $teamsListRepository;
        $this->validatorsTeamsInfos = $validatorsTeamsInfos;
        $this->validatorsTeamsMembers = $validatorsTeamsMembers;
        $this->signUpTeamRepository = $signUpTeamRepository;
        $this->logHandler = $logHandler;
    }

    /**
     * Fonction qui permet d'obtenir tous les équipes et les members de ceux-ci.
     * @return Result Retourne le résultat de l'opération
     */
    public function get_all_teams_and_members(): Result
    {
        try {
            $response = $this->teamsListRepository->get_all_teams_and_members("Participants");

            if (count($response) === 0) {
                return new Result(EnumHttpCode::SUCCESS, array('Il n\'a pas d\'équipes ni de membres qui se sont inscrits'));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), $response);
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui retourne le nom et l'id des catégories
     * @return Result retourne le résultat du service
     */
    public function get_all_categories(): Result {
        try {
            $response = $this->teamsListRepository->get_categories();

            if (count($response) === 0) {
                return new Result(EnumHttpCode::SUCCESS, array("Il n\'a pas de catégories dans la base de données"));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), $response);
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    } 

    /**
     * Obtiens la liste des équipes avec leurs membres regroupés
     * @return Result Retourne le résultat de l'opération
     */
    public function get_all_teams_and_members_concat(): Result
    {
        try {

            $response = $this->teamsListRepository->get_all_teams_and_members_concat("Participants");

            if (count($response) === 0) {
                return new Result(EnumHttpCode::SUCCESS, array('Il n\'a pas d\'équipes ni de membres qui se sont inscrits'));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), $response);
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Permets d'obtenir l'équipe avec tous ses membres selon son id.
     * @param int $id
     * @return Result Retourne le résultat de l'opération.
     */
    public function get_team_and_members(int $id): Result
    {
        try {
            $response = $this->teamsListRepository->get_team_and_members($id);

            if ($response == null) {
                return new Result(EnumHttpCode::NOT_FOUND, array('L\'équipe que vous recherchez n\'existe pas.'));
            }

            return new Result(EnumHttpCode::SUCCESS, array("Success"), array("team" => $response));
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'obtention des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet d'ajouter un membre à une équipe
     * @param  array $teamMember
     * @return Result Retourne le résultat de l'opération
     */
    public function add_team_member(array $teamMember): Result
    {
        $validationResult = $this->validatorsTeamsMembers->validateTeamsMembers($teamMember['member']);

        //Vérification des erreurs de validation des champs
        if ($validationResult->get_http_code() != EnumHttpCode::SUCCESS) {
            return $validationResult;
        }
        
        try {
            //Création d'un objet TeamMember
            $member = new TeamMember($teamMember['member']);

            $response = $this->teamsListRepository->add_team_member($member);

            if ($response === false) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Il n’y a eu aucune modification."));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Le membre a bien été ajouté.');
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'enregistrement des données."), $e);
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet de mettre à jour les informations d'une équipe
     * @param  array $teamInfo
     * @return Result Retourne le résultat de l'opération
     */
    public function update_teams_infos(array $teamInfo): Result
    {

        $resultTeam = $this->validatorsTeamsInfos->validateTeamsInfos($teamInfo['team']);

        //Vérification des erreurs de validation des champs
        if ($resultTeam->get_http_code() !== EnumHttpCode::SUCCESS) {
            return $resultTeam;
        }

        //Création d'un objet Team
        $team = new TeamInfo($teamInfo['team']);

        try {
            $response = $this->teamsListRepository->update_team_info($team);

            if ($response === false) {
                // Pas une erreur, mais aucune modification
                return new Result(EnumHttpCode::SUCCESS, array("Aucune modification : les données sont identiques à l'existant."));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), 'L\'équipe a bien été mise à jour.');
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'enregistrement des données."), $e);
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet de mettre à jour les informations d'un membre d'une équipe
     * @param  array $teamMember
     * @return Result Retourne le résultat de l'opération
     */
    public function update_team_member(array $teamMember): Result
    {
        $validationResult = $this->validatorsTeamsMembers->validateTeamsMembers($teamMember['member']);
        //Vérification des erreurs de validation des champs
        if ($validationResult->get_http_code() != EnumHttpCode::SUCCESS) {
            return $validationResult;
        }

        $member = new TeamMember($teamMember['member']);

        try {
            $response = $this->teamsListRepository->update_team_member($member);
            if ($response === false) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Il n’y a eu aucune modification."));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Le membre a bien été mise à jour.');
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur est survenu lors de l'enregistrement des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet de mettre à jour les numéros des équipes
     * @param  array $teamNumber
     * @return Result Retourne le résultat de l'opération
     */
    public function update_teams_numbers(array $teams): Result
    {
        try {
            $response = $this->teamsListRepository->update_teams_numbers($teams["team"]);

            if ($response === false) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Il n’y a eu aucune modification."));
            }

            return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Les équipes ont bien été mise à jour.');
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de l'enregistrement des données."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet de supprimer un ou plusieurs membres de l'équipe
     * @param  array $teamMember
     * @return Result Retourne le résultat de l'opération
     */
    public function delete_teams_members(array $teamMember): Result
    {
        try {
            $response = $this->signUpTeamRepository->delete_all_team_members($teamMember['team']);

            if (sizeof($response) == 0) {
                return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Les membres ont bien été supprimé.');
            }

            return new Result(EnumHttpCode::BAD_REQUEST, array('Il n’y a eu aucune modification.'));
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de la suppression des membres de l'équipe"));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet de supprimer une ou plusieurs équipe
     * @param  array $teamInfo
     * @return Result Retourne le résultat de l'opération
     */
    public function delete_teams_infos(array $teamInfo): Result
    {
        try {
            $response = $this->signUpTeamRepository->delete_all_team($teamInfo['team']);

            if (sizeof($response) == 0) {
                return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Les équipes ont bien été supprimé.');
            }

            return new Result(EnumHttpCode::BAD_REQUEST, array('Une erreur est survenu lors de la suppression des équipes.'));
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de la suppression des équipes"));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }
}
