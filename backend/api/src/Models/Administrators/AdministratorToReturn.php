<?php

namespace App\Models\Administrators;

/**
 * Structure des valeurs d'un administrateur à retourner au client.
 * 
 * (On ne veut pas retourner le mot de passe. Il doit rester sur le backend.
 * Les validations se font sur le backend.)
 *
 * Propriétés :
 * - int $id : ID de l'administrateur.
 * - string $email : Email de l'administrateur.
 * @author Antoine Ouellette
 * @package App\Models
 */
class AdministratorToReturn
{
    public int $id;
    public string $email;

    /**
     * Constructeur de la classe AdministratorToReturn.
     * @param array $administratorAsDictionary Un administrateur sous forme de tableau associatif.
     */
    public function __construct(array $administratorAsDictionary)
    {
        $this->id = $administratorAsDictionary["id"];
        $this->email = $administratorAsDictionary["email"];
    }
}