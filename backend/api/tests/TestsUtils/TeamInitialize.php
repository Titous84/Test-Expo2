<?php
namespace Test\TestsUtils;

use App\Models\Team;
use App\Utils\GeneratorUUID;

/**
 * TeamInitialize
 * @author Tristan Lafontaine
 * Classe qui initialise des équipes pour les tests
 */
class TeamInitialize{
    /**
	 * Team
	 * Initialise une équipe
	 * @return Team
	 */
	public function Team(): Team
	{
		TestingLogger::log("Création d'une équipe");
		return new Team(array(
			"title" => "Informatique",
			"description" => "Description",
			"category" => "Humain",
			"year" => "1er année",
			"contactPerson" => array(
				array(
					"fullName" => "Test Test",
					"email" => "test@cegepvicto.ca",
				)
			),
			"members" => array(
				array(
					"firstName" => "testfirsname",
					"lastName" => "testlastname",
					"email" => "test@gmail.com",
					"pictureConsent" => 1,
				),
				array(
					"firstName" => "testtwofirtname",
					"lastName" => "testtwolastname",
					"email" => "test@live.ca",
					"pictureConsent" => 0,
				)
			)
		));
	}

	/**
	 * Team
	 * Initialise une équipe
	 * @return Team
	 */
	public function Team_different_category(): Team
	{
		TestingLogger::log("Création d'une équipe");
		return new Team(array(
			"title" => "Informatique",
			"description" => "Description",
			"category" => "Projet TES",
			"year" => "1er année",
			"contactPerson" => array(
				array(
					"fullName" => "Test Test",
					"email" => "test@cegepvicto.ca",
				)
			),
			"members" => array(
				array(
					"firstName" => "testfirsname",
					"lastName" => "testlastname",
					"email" => "test@gmail.com",
					"pictureConsent" => 1,
				),
				array(
					"firstName" => "testtwofirtname",
					"lastName" => "testtwolastname",
					"email" => "test@live.ca",
					"pictureConsent" => 1,
				)
			)
		));
	}

	/**
	 * Team
	 * Initialise une équipe
	 * @return Team
	 */
	public function TeamTwo(): Team
	{
		TestingLogger::log("Création d'une équipe");
		return new Team(array(
			"title" => "Informatique",
			"description" => "Description",
			"category" => "Humain",
			"year" => "1er année",
			"contactPerson" => array(
				array(
					"fullName" => "Test Test",
					"email" => "test@cegepvicto.ca",
				)
			),
			"members" => array(
				array(
					"firstName" => "FirstName",
					"lastName" => "LastName",
					"email" => "testTwo@gmail.com",
					"pictureConsent" => 1,
				),
				array(
					"firstName" => "FirstName2",
					"lastName" => "LastName2",
					"email" => "testTwo@live.ca",
					"pictureConsent" => 1,
				)
			)
		));
	}

		/**
	 * Team
	 * Initialise une équipe
	 * @return Team
	 */
	public function TeamThree(): Team
	{
		TestingLogger::log("Création d'une équipe");
		return new Team(array(
			"title" => "Informatique",
			"description" => "Description",
			"category" => "Humain",
			"year" => "1er année",
			"contactPerson" => array(
				array(
					"fullName" => "test Test",
					"email" => "test@cegepvicto.ca",
				)
			),
			"members" => array(
				array(
					"firstName" => "FirstName",
					"lastName" => "LastName",
					"email" => "test@gmail.com",
					"pictureConsent" => 1,
				),
				array(
					"firstName" => "FirstName",
					"lastName" => "LastName",
					"email" => "test@gmail.com",
					"pictureConsent" => 1,
				)
			)
		));
	}
}