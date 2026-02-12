<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorUser
 * Permet de valider un code de validation.
 * @author Christopher Boisvert
 * @package App\Validators
 */
class ValidatorVerificationCode extends Validator
{     
    /**
     * Fonction qui permet de valider les données reçues du code de vérification.
     * @param string $codeJSON JSON du code de vérification.
     * @author Maxime Demers Boucher
	 * Fortement inspiré de ValidatorUser
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate( $email ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if($this->is_empty($email))
		{
			array_push($messages, "Le email n'est pas présent.");
		}
        if($this->verify_email($email))
		{
			array_push($messages, "Le courriel n'est pas valide.");
		}
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}