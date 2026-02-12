<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorUserRole
 * Permet de valider un rôle associé à un utilisateur.
 * @author Christopher Boisvert
 * @package App\Validators
 */
class ValidatorUserRole extends Validator
{     
    /**
     * Fonction qui permet de valider un UserRole.
     * @param string $userRoleJSON Données du rôle associé à un utilisateur sous forme JSON.
     * @author Christopher Boisvert
     * @return Result
     */
    public function validate( $userRoleJSON ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if(!isset($userRoleJSON["email"])){
            array_push($messages, "Le champ 'email' se doit d'être fourni !");
        }
        if(isset($userRoleJSON["email"]) && !strlen($userRoleJSON["email"])){
            array_push($messages, "Le champ 'email' ne peut être vide.");
        }
        if(isset($userRoleJSON["email"]) && $this->verify_email($userRoleJSON["email"]))
        {
            array_push($messages, "Le champ 'email' est invalide. Veuillez suivre le format nom@domaine.com.");
        }
        if(!isset($userRoleJSON["role"])){
            array_push($messages, "Le champ 'role' n'a pas été entré.");
        }
        if(isset($userRoleJSON["role"]) && !strlen($userRoleJSON["role"]))
        {
            array_push($messages, "Le champ 'role' ne peut être vide.");
        }
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}