<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Credential;
use App\Models\Result;
use App\Repositories\ResultatRepository;
use Exception;
use PDOException;



/**
  * Souleymane Soumaré
 * Service pour obtenir resultat.
 */
final class ResultatService
{
    /**
     * @var ResultatRepository
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param ResultatRepository $repository The repository
     */
    public function __construct(ResultatRepository $repository)
    {
        $this->repository = $repository;
    }

    public function showingResultat() : Result
    {
        try {
            $resultats = $this->repository->selectResultats();
            if ( count($resultats) == 0 || $resultats === null) {
                return new Result(EnumHttpCode::SUCCESS, array("Aucun résultat à afficher"));
            }
            return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les résultats !"), $resultats);
        }
        catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'affichage des résultats"));
        }
    }
    
    /**
     * Fonction qui permet de supprimer le résultat d'un juge pour une évaluation
     * @param  int $id
     * @return Result Retourne le résultat de l'opération
     */
    public function delete_judge_resultat(string $teamName, int $judgeId ): Result
    {
        try {
            $response = $this->repository->delete_judge_resultat($teamName, $judgeId);

            if ($response) {
                return new Result(EnumHttpCode::SUCCESS, array('Success'), 'Les résultats ont bien été supprimé.');
            }

            return new Result(EnumHttpCode::BAD_REQUEST, array('Il n’y a eu aucune modification.'));
        } catch (PDOException $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenu lors de la suppression des résultats."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

}
