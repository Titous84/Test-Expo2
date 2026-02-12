<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorCommentResult
 * Permet de valider le changement de commentaire d'un formulaire.
 * @author Jean-Christophe Demers
 * @package App\Validators
 */
class ValidatorCommentResult extends Validator
{     
    /**
     * Fonction qui permet de valider les données reçues concernant le commentaire du formulaire.
     * @param string $questionResultJSON
     * @author Jean-Christophe Demers
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate( $questionResultJSON ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if(!isset($questionResultJSON["comment"])){
            array_push($messages, "Le commentaire se doit d'être fourni !");
        }

        if(!isset($questionResultJSON["evaluation_id"])){
            array_push($messages, "L'identitifant de l'évaluation n'est pas présent.");
        }

        if(isset($questionResultJSON["evaluation_id"]) && !is_int($questionResultJSON["evaluation_id"])){
            array_push($messages, "L'identitifant de l'évaluation se doit d'être un nombre !");
        }

        if(isset($questionResultJSON["evaluation_id"]) && $questionResultJSON["evaluation_id"] <= 0){
            array_push($messages, "L'identitifant de l'évaluation se doit d'être un nombre plus grand que zéro !");
        }
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"), true);
		return new Result(EnumHttpCode::BAD_REQUEST, $messages, false);
	}
}