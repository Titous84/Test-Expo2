<?php

namespace App\Models;

/**
 * Classe Team.
 * @package App\Models
 * @author Tristan Lafontaine
 */
class Team
{
	/**
	 * @var int|null ID de l'équipe.
	 */
	public $id;
	
	/**
	 * team_number
	 * Number de l'équipe
	 * @var mixed
	 */
	public $team_number;

	/**
	 * @var string Titre du stand.
	 */
	public $title;

	/**
	 * @var string Description du stand.
	 */
	public $description;

	/**
	 * @var string Categorie.
	 */
	public $category;

	/**
	 * @var string|null L'année des participants.
	 */
	public $year;

	/**
	 * @var array[] Personne-ressource
	 */
	public array $contactPerson;

	/**
	 * @var array[] Tableau des membres de l'équipe
	 */
	public array $members;
	
	/**
	 * type_id
	 * Permet de connaitre le type de l'évaluation: DD | SAT | TES
	 * @var int
	 */
	public $type_id;

	/**
	 * Team constructeur.
	 * @param $teamJSON
	 */
    public function __construct($teamJSON)
    {
        $this->id = isset($teamJSON["id"]) ? $teamJSON["id"] : null ;
		$this->team_number = isset($teamJSON["team_number"]) ? $teamJSON["team_number"] : null ;
        $this->title = $teamJSON["title"];
        $this->description = $teamJSON["description"];
		$this->category = $teamJSON["category"];
	    $this->year = $teamJSON["year"];
		$this->contactPerson = $teamJSON["contactPerson"];
		$this->members = $teamJSON["members"];
		$this->type_id = isset($teamJSON["type_id"]) ? $teamJSON["type_id"] : 0;
    }
}