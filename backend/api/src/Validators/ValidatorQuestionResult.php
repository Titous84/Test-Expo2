<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorQuestionResult
 * Permet de valider résultat d'une question d'un formulaire.
 * @author Christopher Boisvert
 * @package App\Validators
 */
class ValidatorQuestionResult extends Validator
{     
    /**
     * Fonction qui permet de valider les données reçues concernant la question du formulaire.
     * @param string $questionResultJSON
     * @author Christopher Boisvert
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate( $questionResultJSON ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if(!isset($questionResultJSON["score"])){
            array_push($messages, "Le score de la question se doit d'être fourni !");
        }

        if(isset($questionResultJSON["score"]) && !is_numeric($questionResultJSON["score"])){
            array_push($messages, "Le score de la question se doit d'être un nombre !");
        }

        if(isset($questionResultJSON["score"]) && $questionResultJSON["score"] < 0){
            array_push($messages, "Le score de la question se doit d'être un nombre plus grand ou égal que zéro !");
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

        if(!isset($questionResultJSON["criteria_id"])){
            array_push($messages, "L'identitifant de la question n'est pas présent.");
        }

        if(isset($questionResultJSON["criteria_id"]) && !is_int($questionResultJSON["criteria_id"])){
            array_push($messages, "L'identitifant de la question se doit d'être un nombre !");
        }

        if(isset($questionResultJSON["criteria_id"]) && $questionResultJSON["criteria_id"] <= 0){
            array_push($messages, "L'identitifant de la question se doit d'être un nombre plus grand que zéro !");
        }
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}

    /**
     * Fonction qui permet de valider les données reçues pouvant rechercher un formulaire.
     * @param string $questionResultJSON
     * @author Christopher Boisvert
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate_with_score_null( $questionResultJSON ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        if(!isset($questionResultJSON["evaluation_id"])){
            array_push($messages, "L'identitifant de l'évaluation n'est pas présent.");
        }

        if(isset($questionResultJSON["evaluation_id"]) && !is_int($questionResultJSON["evaluation_id"])){
            array_push($messages, "L'identitifant de l'évaluation se doit d'être un nombre !");
        }

        if(isset($questionResultJSON["evaluation_id"]) && $questionResultJSON["evaluation_id"] <= 0){
            array_push($messages, "L'identitifant de l'évaluation se doit d'être un nombre plus grand que zéro !");
        }

        if(!isset($questionResultJSON["criteria_id"])){
            array_push($messages, "L'identitifant de la question n'est pas présent.");
        }

        if(isset($questionResultJSON["criteria_id"]) && !is_int($questionResultJSON["criteria_id"])){
            array_push($messages, "L'identitifant de la question se doit d'être un nombre !");
        }

        if(isset($questionResultJSON["criteria_id"]) && $questionResultJSON["criteria_id"] <= 0){
            array_push($messages, "L'identitifant de la question se doit d'être un nombre plus grand que zéro !");
        }
        
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}