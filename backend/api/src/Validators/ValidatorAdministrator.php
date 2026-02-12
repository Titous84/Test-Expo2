<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * Middleware de validation pour les administrateurs.
 * @author Antoine Ouellette
 * @package App\Validators
 */
class ValidatorAdministrator extends Validator
{
    /**
     * Valide les données reçues pour un administrateur.
     * @author Antoine Ouellette
     * @param $administratorDictionary Un tableau associatif contenant les données de l'administrateur.
     * @return Result Un résultat qui sera retourné au client.
     */
    public function validate($administratorDictionary): Result
    {
        if
        (
            $this->is_empty($administratorDictionary["email"]) ||
            $this->is_empty($administratorDictionary["password"]) ||
            $this->verify_email($administratorDictionary["email"]) // Retourne true si l'email est invalide.
        )
        {
            // Si invalide.
            return new Result(EnumHttpCode::BAD_REQUEST, array("Le corps de la requête est invalide."));
        }

        // Si valide.
        return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
    }
}