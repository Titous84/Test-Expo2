<?php

namespace App\Models;

/**
 * Classe TeamInfo.
 * @package App\Models
 * @author Tristan Lafontaine
 */
class TeamInfo
{
	/**
	 * @var int|null ID de l'équipe.
	 */
	public $id;
	
	/**
	 * team_number
	 * Number de l'équipe
	 * @var string
	 */
	public $teamNumber;

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
     * survey
     *
     * @var string Type d'évaluation
     */
    public $survey;
    
    /**
     * teamsActivated
     *
     * @var string
     */
    public $teamsActivated;

	/**
	 * @var string|null L'année des participants.
	 */
	public $year;
	
	/**
	 * type_id
	 * Permet de connaitre le type de l'évaluation: DD | SAT | TES
	 * @var int
	 */
	public $typeId;

	/**
	 * Team constructeur.
	 * @param $teamJSON
	 */
    public function __construct($teamJSON)
    {
        $this->id = isset($teamJSON["team_id"]) ? $teamJSON["team_id"] : null ;
		$this->teamNumber = isset($teamJSON["team_number"]) ? $teamJSON["team_number"] : null ;
        $this->title = $teamJSON["title"];
        $this->description = $teamJSON["description"];
		$this->category = $teamJSON["category"];
        $this->survey = $teamJSON["survey"];
        $this->teamsActivated = $teamJSON["teams_activated"];
	    $this->year = $teamJSON["year"];
		$this->typeId = isset($teamJSON["type_id"]) ? $teamJSON["type_id"] : 0;
    }
}