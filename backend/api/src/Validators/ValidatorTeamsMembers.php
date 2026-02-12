<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorTeam
 * Permet de valider chaque champs d'un objet Team
 * @author Tristan Lafontaine
 * @package App\Validators
 */
class ValidatorTeamsMembers extends Validator
{
    /**
     * validate
     * Permet de valider les champs d'une équipe
     * @param  mixed $member
     * @author Tristan Lafontaine
     * @return Result
     */
    public function validateTeamsMembers(array $member): Result
    {
        $messages = [];

        // Vérifications de l'adresse de courriel.
        if (isset($member['email']) && !$this->is_empty($member['email'])) {
            if ($this->verify_email($member['email'])) {
                $messages[] = "L'adresse courriel est invalide.";
            }
        }

        // Vérications du prénom
        if (!isset($member['first_name'])) {
            $messages[] = 'Le champ "first_name" est obligatoire. ';
        } else if ($this->is_empty($member['first_name'])) {
            $messages[] = "Le prénom ne doit pas être vide.";
        }

        //Vérications du nom
        if (!isset($member['last_name'])) {
            $messages[] = 'Le champ "last_name" est obligatoire. ';
        } else if ($this->is_empty($member['last_name'])) {
            $messages[] = "Le nom ne doit pas être vide.";
        }

        //Vérications du consentement à la photo
        if (!isset($member['picture_consent'])) {
            $messages[] = 'Le champ "picture_consent" est obligatoire.';
        } else if ($member['picture_consent'] !== 0 && $member['picture_consent'] !== 1) {
            $messages[] = "Le consentement à la photo doit être 1 (oui) ou 0 (non). ";
        }

        //Vérications de l'activation
        if (!isset($member['users_activated'])) {
            $member['users_activated'] = 0; // Valeur par défaut si non fournie
        } else if ($member['users_activated'] !== 0 && $member['users_activated'] !== 1) {
            $messages[] = "L'activation doit être 1 (activé) ou 0 (non-activé). ";
        }

        // Vérifications de l'équipe associée (team_id)
        if (!isset($member['team_id'])) {
            $messages[] = 'Le champ "team_id" est obligatoire.';
        } else if (!is_numeric($member['team_id']) || $member['team_id'] <= 0) {
            $messages[] = "L'ID de l'équipe doit être un entier positif.";
        }

        if (sizeof($messages) === 0) {
            return new Result(EnumHttpCode::SUCCESS, array("Validation réussie"));
        }
        return new Result(EnumHttpCode::BAD_REQUEST, $messages);
    }
}