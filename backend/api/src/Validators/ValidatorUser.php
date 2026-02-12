<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorUser
 * Permet de valider un utilisateur.
 * @author Christopher Boisvert
 * @package App\Validators
 */
class ValidatorUser extends Validator
{     
    /**
     * Fonction qui permet de valider les données reçues concernant la question du formulaire.
     * @param string $userJSON JSON de l'utilisateur.
     * @author Christopher Boisvert
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate( $userJSON ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if($this->is_empty($userJSON["first_name"]))
		{
			array_push($messages, "Le prénom n'est pas présent.");
		}
		if($this->is_empty($userJSON["last_name"]))
		{
			array_push($messages, "Le nom n'est pas présent.");
		}
		if($this->is_empty($userJSON["username"]))
		{
			array_push($messages, "Le nom d'utilisateur n'est pas présent.");
		}
		if($this->is_empty($userJSON["pwd"]))
		{
			array_push($messages, "Le mot de passe n'est pas présent.");
		}
		if($this->is_empty($userJSON["email"]))
		{
			array_push($messages, "Le courriel n'est pas présent.");
		}
        if($this->verify_email($userJSON["email"]))
		{
			array_push($messages, "Le courriel n'est pas valide.");
		}
		if($this->is_empty($userJSON["role_id"]))
		{
			array_push($messages, "Le rôle n'est pas présent.");
		}
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}