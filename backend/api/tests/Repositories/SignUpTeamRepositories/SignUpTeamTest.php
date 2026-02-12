<?php

namespace Repositories\SignUpTeamRepositories;

use App\Enums\EnumHttpCode;
use App\Handlers\LogHandler;
use App\Repositories\SignUpTeamRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Utils\GeneratorUUID;
use Test\TestsUtils\TeamInitialize;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester le service de courriel.
 * @author Tristan Lafontaine
 * @package Repositories\SignUpTeamRepositories
 */
final class SignUpTeamTest extends TestCase {

	private static $pdo;
	
	/**
	 * setUpBeforeClass
	 * Permet de créer une instance de PDO
	 * @return void
	 */
	public static function setUpBeforeClass() : void
	{
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../../.env.prod');
		self::$pdo = new PDOInitialize();
	}
	
	/**
	 * tearDownAfterClass
	 * Permet de supprimer l'instance du PDO
	 * @return void
	 */
	public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }

	/**
	 * test_add_team_signup
	 * Fonction pour tester l'ajout d'un utilisateur dans la base de données.
	 * @author Tristan Lafontaine
	 * @return void
	 */
	public function test_add_team_signup(){
		TestingLogger::log("Changement de la variable ENV en mode développement");
		$_ENV["production"] = "false";


		TestingLogger::log("Création du répertoire SignUpTeamRepository");
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$teamInitialize = new TeamInitialize();
		$team = $teamInitialize->Team();

        TestingLogger::log("Appel de la fonction pour générer les UUIDs");

        $token = GeneratorUuid::generate_UUID_array(sizeOf($team->members));

        $response = $signUpTeamRepository->add_team($team, $token);

		TestingLogger::log($response->to_json());

		$this->assertEquals(EnumHttpCode::CREATED,$response->get_http_code(),"Erreur : test_add_team_signup");
	}
	
	/**
	 * test_check_email_is_not_BD
	 * Fonction qui permet de tester si les adresse courriel de la nouvelle équipe est présent dans la based de données
	 * @return void
	 */
	public function test_check_email_is_not_BD_error() {

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->Team();

		$response = $signUpTeamRepository->check_email_is_not_BD($team);

		$this->assertGreaterThan(0,sizeof($response),"Erreur : test_check_email_is_not_BD_error");
	}

	/**
	 * test_delete_team
	 * Fonction qui permet de tester la suppresion d'une équipe ou de plusieurs équipes
	 * @return void
	 */
	public function test_delete_team(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository \n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		echo date("Y-m-d h:m:s") . " Obtenir tous les équipes de la bd. \n";
		$teams = $signUpTeamRepository->get_all_team_by_title_and_description("Informatique","Description");

		$sizeofArray = sizeof($teams);
		for($a = 0; $a < $sizeofArray; $a++){
			$response = $signUpTeamRepository->delete_team($teams[$a]["id"]);

			$this->assertEquals(1,$response, "Erreur : test_delete_team");
		}
	}

	/**
	 * test_check_email_is_not_BD
	 * Fonction qui permet de tester si les adresse courriel de la nouvelle équipe n'est pas déjà présente dans la base de données
	 * @return void
	 */
	public function test_check_email_is_not_BD(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->Team();

		$response = $signUpTeamRepository->check_email_is_not_BD($team);

		$this->assertEquals(0,sizeof($response), "Erreur : test_check_email_is_not_BD");
	}
	
	/**
	 * test_get_category
	 * Fonction qui permet de tester la récupération de l'id d'une catégorie
	 * @return void
	 */
	public function test_get_category(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$response = $signUpTeamRepository->get_category("Humain");

		$this->assertEquals(2,$response["id"],"Erreur : test_get_category");
	}
	
	/**
	 * test_get_contact_person_by_email
	 * Fonction qui permet de tester la récupération d'une personne ressource à partir de son adresse courriel
	 * @return void
	 */
	public function test_get_contact_person_by_email(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->Team();

		$signUpTeamRepository->add_contact_person($team);

		$response = $signUpTeamRepository->get_contact_person_by_email($team->contactPerson[0]["email"]);

		$this->assertEquals($team->contactPerson[0]["email"],$response["email"],"Erreur : test_get_contact_person_by_email");
	}
	
	/**
	 * test_get_all_team
	 * Fonction qui permet de récupération tous les équipes de la bd
	 * @return void
	 */
	public function test_get_all_team(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		echo date("Y-m-d h:m:s") . " Appel la classe GeneratorUuid\n";
		$generatorUUID = new GeneratorUuid();

		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->Team();
		$teamTwo = $teamInitialize->TeamTwo();

		echo date("Y-m-d h:m:s") . " Appel la fonction pour générer les UUIDs \n";
        $token = $generatorUUID->generate_UUID_array(sizeOf($team->members));

		echo date("Y-m-d h:m:s") . " Ajout de l'équipe 1 dans la bd \n";
		$signUpTeamRepository->add_team($team, $token);

		echo date("Y-m-d h:m:s") . " Appel la fonction pour générer les UUIDs";
        $tokenTwo = $generatorUUID->generate_UUID_array(sizeOf($teamTwo->members));

		echo date("Y-m-d h:m:s") . " Ajout de l'équipe 2 dans la bd \n";
		$signUpTeamRepository->add_team($teamTwo, $tokenTwo);

		echo date("Y-m-d h:m:s") . " Appel la fonction pour obtenir toutes les équipes \n";
		$response = $signUpTeamRepository->get_all_team_by_title_and_description("Informatique","Description");

		$this->assertGreaterThan(0,$response,"Erreur : test_get_all_team");

		$this->test_delete_team();
	}
	
	/**
	 * test_get_team
	 *	Fonction qui permet de récupération une seul équipe à partir de son titre du stand et son id
	 * @return void
	 */
	public function test_get_team(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$this->test_add_team_signup();

		$response = $signUpTeamRepository->get_all_team_by_title_and_description("Informatique","Description");

		echo date("Y-m-d h:m:s") . " Appel la fonction pour obtenir toutes les équipes \n";
		$response = $signUpTeamRepository->get_team($response[0]["id"]);

		$this->assertEquals("Informatique", $response["name"],"Erreur : test_get_team");

		$this->test_delete_team();
	}

	
	/**
	 * test_get_member_by_email
	 * Fonction qui permet de récupérer un membre à partir d'une adresse courriel
	 * @return void
	 */
	public function test_get_member_by_email(){
		$this->test_add_team_signup();

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$response = $signUpTeamRepository->get_member_by_email("test@gmail.com");

		$this->assertEquals("test@gmail.com",$response["email"],"Erreur : test_get_member_by_email");

		$this->test_delete_team();
	}
	
	/**
	 * test_get_members_team
	 * Fonction qui permet de récupérer tous les ids des members d'une équipe
	 * @return void
	 */
	public function test_get_members_team(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$this->test_add_team_signup();

		$response = $signUpTeamRepository->get_all_team_by_title_and_description("Informatique","Description");

		echo date("Y-m-d h:m:s") . " Appel la fonction pour obtenir toutes les équipes \n";
		$response = $signUpTeamRepository->get_team($response[0]["id"]);

		echo date("Y-m-d h:m:s") . " Appel la fonction pour obtenir tous les membres d'une équipe \n";
		$response = $signUpTeamRepository->get_members_team($response["id"]);

		$this->assertEquals(2,sizeof($response),"Erreur : test_get_members_team");

		$this->test_delete_team();

	}
	
	/**
	 * test_check_email_duplicate
	 * Fonction qui tester si il n'a pas la même adresse courriel dans une équipe
	 * @return void
	 */
	public function test_check_email_duplicate(){
		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->Team();

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$response = $signUpTeamRepository->check_email_duplicate($team);

		$this->assertEquals(0,sizeOf($response),"Erreur : test_check_email_duplicate");
	}

	/**
	 * test_check_email_duplicate
	 * Fonction qui tester si il a y la même adresse courriel dans une équipe
	 * @return void
	 */
	public function test_check_email_duplicate_error(){
		$teamInitialize = new TeamInitialize();

		$team = $teamInitialize->TeamThree();

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$response = $signUpTeamRepository->check_email_duplicate($team);

		$this->assertGreaterThan(0,sizeOf($response),"Erreur : test_check_email_duplicate_error");
	}
	
	/**
	 * test_created_two_teams_but_different_category
	 * Permets de vérifier qui retourne bien une erreur lors 
	 * d'une insertion d'une nouvelle équipe avec un membre déjà inscrit, 
	 * mais que l'adresse courriel n'est pas encore valider.
	 * @return void
	 */
	public function test_created_two_teams_but_different_category(){
		$teamInitialize = new TeamInitialize();

		$teamOne = $teamInitialize->Team();
		$teamTwo = $teamInitialize->Team_different_category();

		$tokenTeamOne = GeneratorUuid::generate_UUID_array(sizeOf($teamOne->members));

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$signUpTeamRepository->add_team($teamOne, $tokenTeamOne);
		//Vérifie si l'équipe est activé
		$teamActive = $signUpTeamRepository->check_team_active($teamTwo);
            
		$messageErreur = [];

		//Vérification si l'équipe est activé
		for($t = 0; $t < sizeof($teamActive); $t++){
			if($teamActive[$t] != false){
				$messageErreur[] = $teamActive[$t]["first_name"] . " " . $teamActive[$t]["last_name"] . ", vous devez activer votre compte avant de créer une nouvelle équipe.";
			}
		}

		$this->assertEquals(2, sizeof($messageErreur),"Erreur : test_created_two_teams_but_different_category");

		$this->test_delete_team();
	}

	/**
	 * test_uppercase_first_letter
	 * 
	 * @return void
	 */
	public function test_uppercase_first_letter(){

		echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
		$logHandler = new LogHandler();
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		$response = $signUpTeamRepository->uppercase_first_letter("tristan");

		$this->assertEquals("Tristan",$response,"Erreur : test_uppercase_first_letter");
	}
}
