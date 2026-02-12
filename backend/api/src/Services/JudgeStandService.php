<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Handlers\LogHandler;
use App\Models\Credential;
use App\Models\Result;
use App\Repositories\JudgeStandRepository;


/**
 * Souleymane Soumaré
 * Service pour obtenir juges.
 */
final class JudgeStandService
{
    /**
     * @var JudgeStandRepository
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param JudgeStandRepository $repository The repository
     */
    public function __construct(JudgeStandRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @author Xavier Houle (Auteur Principal Inconnu, je ne veux pas prendre le crédit de la fonction)
     * Fonction qui retourne les juges
     * @return Result Tous les juges de la base de données.
     */
    public function get_judge() : Result
    {
        $resultats = $this->repository->selectJudge();
        return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les résultats !"), $resultats);
    }

    /**
     * @author Xavier Houle
     * Fonction pour ajouter une évaluation.
     * @param array $data Les données de l'évaluation.
     * @return Result Le résultat de l'ajout.
     */
    public function add_evaluation(array $data) : Result
    {
        $result = $this->repository->add_evaluation($data);
        return new Result(EnumHttpCode::CREATED, array("Nous avons ajouter l'évaluation !"), $result);
    }

    /**
     * @author Xavier Houle
     * Fonction pour modifier une évaluation.
     * @param array $data Les données de l'évaluation.
     * @return Result Le résultat de la modification.
     */
    public function update_evaluation(array $data) : Result 
    {
        $result = $this->repository->update_evaluation($data);
        return new Result(EnumHttpCode::SUCCESS, array("Nous avons modifier l'évaluation !"), $result);
    }


    /**
     * @author Xavier Houle
     * Fonction pour supprimer une évaluation.
     * @param array $data Les données de l'évaluation.
     * @return Result Le résultat de la suppresion.
     */
    public function delete_evaluation(int $id) : Result {
        $result = $this->repository->delete_evaluation($id);
        return new Result(EnumHttpCode::SUCCESS, array("L'évaluation a été supprimée!"), $result);
    }

    /**
     * @author Xavier Houle (Auteur Principal Inconnu, je ne veux pas prendre le crédit de la fonction)
     * Fonction qui retourne les heures de passages
     * @return Result Tous les heures de passages de la base de données.
     */
    public function get_time_slots() : Result
    {
        $resultats = $this->repository->selectTimeSlots();
        return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les résultats !"), $resultats);
    }
    
    /**
     * Met à jour le statut de suppression de la note globale pour un juge.
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Services de manière à respecter la structure du projet.
     * @param int $judge_id L'ID du juge à mettre à jour.
     * @param bool $globalScoreRemoved Le nouveau statut de suppression du score global.
     * @return Result Le résultat de l'opération encapsulé dans un objet Result.
     */
    public function updateGlobalScoreRemoved(int $judge_id, array $body): Result
    {
        $success = $this->repository->updateGlobalScoreRemoved($judge_id, $body);
        
        if ($success) {
            return new Result(EnumHttpCode::SUCCESS, ["Message" => "Le statut de la note globale a été mis à jour avec succès."], null);
        }
        else {
            return new Result(EnumHttpCode::SERVER_ERROR, ["Message" => "Une erreur est survenue lors de la mise à jour du statut de la note globale."], null);
        }
    }

    /**
     * Récupère l'état actuel des exclusions de la note globale pour initialiser les "checkboxes".
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Services de manière à respecter la structure du projet.
     * @return Result Le résultat contenant les états d'exclusion de la note globale.
     */
    public function getScoreExclusions(): Result
    {
        $exclusions = $this->repository->fetchScoreExclusions();
        if (!empty($exclusions)) {
            return new Result(EnumHttpCode::SUCCESS, ["Nous avons trouvé les exclusions de la note globale avec succès !"], $exclusions);
        } else {
            return new Result(EnumHttpCode::NOT_FOUND, ["Aucune exclusion de la note globale trouvée."], []);
        }
    }

    /**
     * @author Xavier Houle
     * Fonction pour modifier tous les heures de passages.
     * @param array $data Les nouvelles heures de passages.
     * @return Result Si la modification a été modifier
     */
    public function save_time_slots(array $data) : Result {
        $resultats = $this->repository->update_time_slots($data);
        if ($resultats) {
            return new Result(EnumHttpCode::SUCCESS, array("Les heures de passages ont été enregistrés!"));
        }

        return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenue lors de l'enregistrement."));
    }

    /**
     * @author Alexis Boivin
     * Fonction pour ajouter une heure de passages.
     * @return Result Si l'ajout est réussi.
     */
    public function add_time_slot(array $data) : Result {
        $resultats = $this->repository->add_time_slot($data);
        if ($resultats) {
            return new Result(EnumHttpCode::SUCCESS, array("La nouvelle heure de passage a été enregistrée."));
        }

        return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenue lors de l'enregistrement."));
    }
    /**
     * @author Alexis Boivin
     * Fonction pour supprimer une heure de passages.
     * @return Result Si l'ajout est réussi.
     */
    public function delete_time_slot() : Result {
        $resultats = $this->repository->delete_time_slot();
        if ($resultats) {
            return new Result(EnumHttpCode::SUCCESS, array("L'heure de passage a été supprimé avec succès."));
        }

        return new Result(EnumHttpCode::BAD_REQUEST, array("Une erreur est survenue lors de la suppression."));
    }
}

