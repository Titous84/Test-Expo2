<?php

namespace App\Models;

/**
 * Classe TeamMember.
 * @package App\Models
 * @author Tristan Lafontaine
 */
class TeamMember
{
    /**
     * @var int|null ID de l'équipe.
     */
    public $id;

    /**
     * @var string Numéro de DA
     */
    public $numeroDa;

    /**
     * @var string Prénom
     */
    public $firstName;

    /**
     * @var string Nom
     */
    public $lastName;

    /**

     * @var int Consentement à la photo
     */
    public $pictureConsent;

    /**
     * @var int Activé
     */
    public $userActivated;

    /**
     * @var int|null ID de l'équipe
     */
    public $teamId;

    /**
     * @var string|null Adresse email
     */
    public $email;

    /**
     * @var int Indicateur de mise sur liste noire
     */
    public $blacklisted;

    /**
     * @var int ID de l'enseignant(e)
     */
    public $contactPersonId;

    /**
     * Team constructeur.
     * @param $teamJSON
     */
    public function __construct($teamJSON)
    {
        $this->id = isset($teamJSON["id"]) ? $teamJSON["id"] : null;
        $this->numeroDa = $teamJSON["numero_da"];
        $this->firstName = $teamJSON["first_name"];
        $this->lastName = $teamJSON["last_name"];
        $this->pictureConsent = $teamJSON["picture_consent"];
        $this->userActivated = isset($teamJSON["users_activated"]) ? $teamJSON["users_activated"] : 1;
        $this->teamId = isset($teamJSON["team_id"]) ? $teamJSON["team_id"] : null;
        $this->email = isset($teamJSON["email"]) ? $teamJSON["email"] : null;
        $this->blacklisted = isset($teamJSON["blacklisted"]) ? $teamJSON["blacklisted"] : 0;
        $this->contactPersonId = isset($teamJSON["contact_person_id"]) ? $teamJSON["contact_person_id"] : 0;
    }
}
