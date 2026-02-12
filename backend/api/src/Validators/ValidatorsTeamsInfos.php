<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorsTeamsInfos
 * Permet de valider chaque champs d'un objet TeamInfo
 * @author Tristan Lafontaine
 * @package App\Validators
 */
class ValidatorsTeamsInfos extends Validator
{     
    /**
     * validateTeamsInfos
     * Permet de valider les champs d'une équipe
     * @param  mixed $team
     * @author Tristan Lafontaine
     * @return Result
     */
    public function validateTeamsInfos( array $team ) : Result
    {
        // Tableau d'erreurs de vérification
        $messages = [];

        // Vérification que les champs existent
        if (!isset($team['team_id'])) {
            $messages[] = "L'identifiant de l'équipe est vide.";
        }
        if (!isset($team['team_number'])) {
            $messages[] = "Le numéro de l'équipe est vide.";
        }
        if (!isset($team['title'])) {
            $messages[] = "Le titre est vide.";
        }
        if (!isset($team['description'])) {
            $messages[] = "La description est vide.";
        }
        if (!isset($team['category'])) {
            $messages[] = "La catégorie est vide.";
        }
        if (!isset($team['year'])) {
            $messages[] = "L'année est vide.";
        }
        if (!isset($team['survey'])) {
            $messages[] = "Le modèle d'évaluation est vide.";
        }
        if (!isset($team['teams_activated'])) {
            $messages[] = "Le statut d'activation est vide.";
        }
        if (!isset($team['contact_person_name'])) {
            $messages[] = "Le nom de l'enseignant(e) est vide.";
        }
        if (!isset($team['contact_person_email'])) {
            $messages[] = "L'email de l'enseignant(e) est vide.";
        }
        if (!isset($team['type'])) {
            $messages[] = "Le type est vide.";
        }

        // Vérification des champs s'ils sont vides ou non définis
        if (isset($team['title']) && $this->is_empty($team['title'])) {
            $messages[] = "Le titre du projet est obligatoire.";
        }
        if (isset($team['title']) && strlen($team['title']) > 30) {
            $messages[] = "Le titre du projet ne doit pas dépasser 30 caractères.";
        }
        if (isset($team['description']) && $this->is_empty($team['description'])) {
            $messages[] = "La description du projet est obligatoire.";
        }
        if (isset($team['description']) && strlen($team['description']) > 250) {
            $messages[] = "La description du projet ne doit pas dépasser 250 caractères.";
        }
        if (isset($team['category']) && $this->is_empty($team['category'])) {
            $messages[] = "La catégorie est obligatoire.";
        }
        if (isset($team['year']) && $this->is_empty($team['year'])) {
            $messages[] = "L'année est obligatoire.";
        }
        if (isset($team['survey']) && $this->is_empty($team['survey'])) {
            $messages[] = "Le modèle d'évaluation est obligatoire.";
        }
        if (isset($team['contact_person_email']) && !$this->is_valid_email($team['contact_person_email'])) {
            $messages[] = "L'email de l'enseignant(e) est invalide.";
        }
        if (isset($team['contact_person_name']) && $this->is_empty($team['contact_person_name'])) {
        $messages[] = "Le nom de l'enseignant(e) est obligatoire.";
        }
        if (isset($team['contact_person_email']) && $this->is_empty($team['contact_person_email'])) {
            $messages[] = "L'email de l'enseignant(e) est obligatoire.";
        }
        if (isset($team['contact_person_email']) && !$this->is_valid_email($team['contact_person_email'])) {
            $messages[] = "L'email de l'enseignant(e) est invalide.";
        }
        if (isset($team['type']) && $this->is_empty($team['type'])) {
            $messages[] = "Le type est obligatoire.";
        }
        // Vérification de la validité de certains champs
        if (isset($team['team_id']) && !is_numeric($team['team_id'])) {
            $messages[] = "L'identifiant de l'équipe doit être numérique.";
        }
        if (isset($team['teams_activated']) && !in_array($team['teams_activated'], [0, 1], true)) {
            $messages[] = "Le statut d'activation doit être 0 ou 1.";
        }

        // Retourne le résultat de la validation
        if (sizeof($messages) === 0) {
            return new Result(EnumHttpCode::SUCCESS, ["Validation réussie."]);
        }
        return new Result(EnumHttpCode::BAD_REQUEST, $messages);
    }

    /**
     * Vérifie si une adresse email est valide
     * @param string $email
     * @return bool
     */
    private function is_valid_email(string $email): bool
    {
        // Utilisation de filter_var pour filtrer et valider l'email
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}