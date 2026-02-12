<?php

namespace App\Models\Administrators;

/**
 * Structure des valeurs reçues par l'API pour créer un administrateur.
 *
 * Propriétés :
 * - string $email : Email de l'administrateur.
 * - string $password : Mot de passe de l'administrateur.
 * @author Antoine Ouellette
 * @package App\Models
 */
class AdministratorToCreate
{
    public string $email;
    public string $password;

    /**
     * Constructeur de la classe AdministratorToCreate.
     * @param array $administratorAsDictionary Un tableau associatif contenant les valeurs de l'administrateur.
     */
    public function __construct(array $administratorAsDictionary)
    {
        // Vérifie si les valeurs sont présentes dans le JSON.
        if (!isset($administratorAsDictionary["email"]) || !isset($administratorAsDictionary["password"]))
        {
            throw new \InvalidArgumentException("Les valeurs de l'administrateur ne sont pas valides.");
        }

        $this->email = $administratorAsDictionary["email"];
        $this->password = $administratorAsDictionary["password"];
    }
}