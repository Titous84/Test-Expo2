<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * ValidatorJudge
 * Permet de valider un juge.
 * @author Jean-Philippe Bourassa
 * @package App\Validators
 */
class ValidatorJudge extends Validator
{
    /**
     * Fonction qui permet de valider les données du formulaire d'ajout de juge.
     * @param array $judgeJSON JSON du juge.
     * @author Jean-Philippe Bourassa
	 * @author Étienne Nadeau
     * @return Result Retourne un résultat de la vérification.
     */
    public function validate($judgeJSON): Result
    {
        //Tableau des champs vides
        $emptyFields = [];
        //Tableau d'erreur de vérification pour les formats
        $formatErrors = [];

        if (!$this->verify_field_exists($judgeJSON, "firstName")) {
            array_push($emptyFields, "Prénom");
        }
        if (!$this->verify_field_exists($judgeJSON, "lastName")) {
            array_push($emptyFields, "Nom de famille");
        }
        if (!$this->verify_field_exists($judgeJSON, "email")) {
            array_push($emptyFields, "Courriel");
        } else if ($this->verify_email($judgeJSON["email"])) {
            array_push($formatErrors, "Le courriel est invalide. Le format doit être le suivant : @exemple.ca");
        }
        if (!$this->verify_field_exists($judgeJSON, "category")) {
            array_push($emptyFields, "Catégorie");
        }

        $messages = [];
        if (!empty($emptyFields)) {
			//Inspirer de: https://www.php.net/manual/fr/function.implode.php
            $messages[] = "Les champs suivants n'ont pas été remplis : " . implode(", ", $emptyFields) . ".";
        }
        if (!empty($formatErrors)) {
            $messages = array_merge($messages, $formatErrors);
        }

        if (empty($messages)) {
            return new Result(EnumHttpCode::SUCCESS, ["Validation réussie"]);
        }
        return new Result(EnumHttpCode::BAD_REQUEST, $messages);
    }
}