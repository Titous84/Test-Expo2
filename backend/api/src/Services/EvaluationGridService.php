<?php

namespace App\Services;

use App\Handlers\LogHandler;
use App\Repositories\EvaluationGridRepository;
use App\Enums\EnumHttpCode;
use App\Models\Result;
use Exception;
use PDOException;

/**
 * Class EvaluationGridService
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 * @package App\Services
 */
class EvaluationGridService
{
    /**
     * @var EvaluationGridRepository
     */
    private $evaluationGridRepository;

    /**
     * @var LogHandler
     */
    private $logHandler;

    /**
     * EvaluationGridService constructor.
     * @param EvaluationGridRepository $evaluationGridRepository
     * @param LogHandler $logHandler
     */
    public function __construct(EvaluationGridRepository $evaluationGridRepository, LogHandler $logHandler)
    {
        $this->evaluationGridRepository = $evaluationGridRepository;
        $this->logHandler = $logHandler;
    }

    /**
     * Récupère la liste de toutes les grilles d'évaluation
     * @return Result
     */
    public function getEvaluationGrid() : Result
    {
        try {
            $result = $this->evaluationGridRepository->getEvaluationGrid();
            if (empty($result)) {
                return new Result(EnumHttpCode::BAD_REQUEST, array('Il n\'y a aucune grille d\'évaluation pour le moment'));
            }
            return new Result(EnumHttpCode::SUCCESS, array("Success"), $result);
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
     * Récupère une grille d'évaluation et toutes ses sections et ses critères
     * @param $id
     * @return Result
     */
    public function getEvaluationGridById($id) : Result
    {
        try {
            $result = $this->evaluationGridRepository->getEvaluationGridById($id);
            if (empty($result)) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("La grille d'évaluation n'existe pas"));
            }
            return new Result(EnumHttpCode::SUCCESS, array("Success"), $result);
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
     * Supprime une grille d'évaluation et toutes ses sections et tous ses critères
     * @param $id
     * @return Result
     */
    public function deleteEvaluationGridById($id) : Result
    {
        try {
            if ($this->evaluationGridRepository->deleteEvaluationGridById($id)) {
                return new Result(EnumHttpCode::SUCCESS, array('La grille d\'évaluation a été supprimé avec succès.'));
            }
            return new Result(EnumHttpCode::BAD_REQUEST, array("Aucune modification n'a été apporté à la base de données."));
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
     * Crée une grille d'évaluation et toutes ses sections et tous ses critères
     * @param array $evaluationGridJson
     * @return Result
     */
    public function insertEvaluationGrid(array $evaluationGridJson) : Result
    {
        try {
            $result = $this->evaluationGridRepository->insertEvaluationGrid($evaluationGridJson);
            if ($result === null) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("La grille d'évaluation n'a pas pu être créé."));
            }
            return new Result(EnumHttpCode::CREATED, array("La grille d'évaluation a été créé avec succès."), $result);
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
     * Met à jour une grille d'évaluation et toutes ses sections et tous ses critères
     * @param array $evaluationGridJson
     * @return Result
     */
    public function updateEvaluationGrid(array $evaluationGridJson) : Result
    {
        try {
            if ($this->evaluationGridRepository->updateEvaluationGrid($evaluationGridJson)) {
                return new Result(EnumHttpCode::SUCCESS, array("La grille d'évaluation a été mise à jour avec succès."), true);
            }else{
                return new Result(EnumHttpCode::BAD_REQUEST, array("La grille d'évaluation n'a pas pu être mise à jour."));
            }
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
}