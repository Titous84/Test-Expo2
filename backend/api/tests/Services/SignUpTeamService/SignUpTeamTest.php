<?php

namespace Services\SignUpTeamService;

use App\Handlers\LogHandler;
use App\Repositories\SignUpTeamRepository;
use App\Services\SignUpTeamService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Services\EmailService;
use App\Validators\ValidatorTeam;
use App\Services\TwigService;
use App\Utils\GeneratorUUID;
use Test\TestsUtils\TeamInitialize;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;
/**
 * Classe permettant de tester le service de courriel.
 * @author  Tristan Lafontaine
 * @package Services\SignUpTeamService
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
		//Configuration de l'environnement
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
	 * Fonction qui teste le servie de signUpTeamService
	 * Ce service permet de valider, d'ajouter et d'envoyer les mails.
	 * @return void
	 */
	public function test_add_team_signup(){
		$logHandler = new LogHandler();

		$this->delete_team();
		
		TestingLogger::log("Changement de la variable ENV en mode développement");
		$_ENV["production"] = "false";

		TestingLogger::log("Création du répertoire SignUpTeamRepository");


		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log("Création service de PHP Mailer");
		$phpMailer = new PHPMailer(true);

		TestingLogger::log("Création du service de courriel");
		$emailService = new EmailService($phpMailer);

		TestingLogger::log("Création du validateur d'équipe");
		$validatorTeam = new ValidatorTeam($signUpTeamRepository);

		TestingLogger::log("Création du service Twig");
		$twig = new TwigService();

		TestingLogger::log("Création du service signUpTeamService");
		$signUpTeamService = new SignUpTeamService(
			$signUpTeamRepository,
			$validatorTeam,
			$emailService,
			$twig
		);

		TestingLogger::log("Initialisation de l'équipe");
		$teamInitialize = new TeamInitialize();
		$teamObject = $teamInitialize->Team();
		$team = ["team"=>(array)$teamObject];

		TestingLogger::log("Ajout de l'équipe");
		$response = $signUpTeamService->add_signup_team($team);

		$this->assertEquals($response->get_http_code(),201);
		
		$this->delete_team();
	}

	/**
	 * test_delete_team
	 * Fonction qui permet de tester la suppresion d'une équipe ou de plusieurs équipes
	 * @return void
	 */
	public function delete_team(){
		$logHandler = new LogHandler();

		TestingLogger::log("Création du SignUpTeamRepository");
		$signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log("Obtention de toutes les équipes");
		$teams = $signUpTeamRepository->get_all_team_by_title_and_description("Informatique","Description");

		$sizeofArray = sizeof($teams);
		for($a = 0; $a < $sizeofArray; $a++){
			$response = $signUpTeamRepository->delete_team($teams[$a]["id"]);

			$this->assertEquals(1,$response, "Erreur : test_delete_team");
		}
	}
}