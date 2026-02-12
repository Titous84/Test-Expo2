<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Enums\EnumLengths;
use App\Models\Result;

/**
 * ValidatorUUID
 * Permet de valider un UUID.
 * @author Christopher Boisvert
 * @package App\Validators
 */
class ValidatorUUID extends Validator
{     
    /**
     * validate
     * Permet de valider un UUID
     * @param string $UUID
     * @author Christopher Boisvert
     * @return Result
     */
    public function validate( string $UUID ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = null;

        if(!$this->has_length_of($UUID, EnumLengths::UUID)){
            $messages = array("L'UUID se doit d'être de 36 caractères.");
        }
        
        if($messages == null)
            return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}