<?php

namespace Tests\Repositories\Survey;

use App\Enums\EnumHttpCode;
use App\Repositories\SurveyRepository;
use App\Services\SurveyService;
use App\Handlers\LogHandler;
use App\Models\User;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Utils\GeneratorUUID;
use App\Validators\ValidatorUser;
use Generator;
use PDO;
use Test\TestsUtils\PDOInitialize;

use function PHPSTORM_META\map;

/**
 * Classe permettant de tester les formulaires des juges.
 * @author Christopher Boisvert
 * @package Tests\Repositories\Survey
 */
final class SurveyRepositoryTest extends TestCase {

	private static $pdo;

	private static $mockEvaluation = array(
		"id" => 0,
		"stand_name" => "John Doe Stand",
		"stand_id" => 0,
		"evaluation_start" => "00:00:00",
		"score" => 0,
		"sections" => array()
	);

	private static $mockUser = array(
		"id" => null,
		"first_name" => "John",
		"last_name" => "Doe",
		"username" => "johndoe",
		"email" => "johndoe@johndoe.test",
		"pwd" => "123elite",
		"picture" => null,
		"picture_consent" => 1,
		"activated" => 1,
		"blacklisted" => 1,
		"role_id" => 0
	);
	
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

	public function test_getAllSurveyByJudgeUUIDEmpty()
	{
		echo date("Y-m-d h:m:s") . " Changement de la variable ENV en mode développement\n";
		$_ENV["production"] = "false";

		echo date("Y-m-d h:m:s") . " Création du UUID vide\n";
		$uuid = "";

		echo date("Y-m-d h:m:s") . " Création du log handler et du SurveyRepository\n";
		$logHandler = new LogHandler();
		$surveyRepository = new SurveyRepository(self::$pdo->PDO(), $logHandler);

		echo date("Y-m-d h:m:s") . " Tentative d'obtenir des formulaires avec un uuid vide\n";
		$resultat = $surveyRepository->get_all_survey_by_judge_id($uuid);

		$this->assertEquals($resultat, []);
	}

	public function test_getAllSurveyByJudgeUUIDValidButDoesntExists()
	{
		echo date("Y-m-d h:m:s") . " Changement de la variable ENV en mode développement\n";
		$_ENV["production"] = "false";

		echo date("Y-m-d h:m:s") . " Création du UUID valide\n";
		$uuid = GeneratorUUID::generate_UUID_array(1)[0];

		echo date("Y-m-d h:m:s") . " Création du log handler et du SurveyRepository\n";
		$logHandler = new LogHandler();
		$surveyRepository = new SurveyRepository(self::$pdo->PDO(), $logHandler);

		echo date("Y-m-d h:m:s") . " Tentative d'obtenir des formulaires avec un uuid bien formaté mais invalide\n";
		$resultat = $surveyRepository->get_all_survey_by_judge_id($uuid);

		$this->assertEquals($resultat, []);
	}

	public function test_getAllSurveyByJudgeUUIDValidAndItExists()
	{
		echo date("Y-m-d h:m:s") . " Changement de la variable ENV en mode développement\n";
		$_ENV["production"] = "false";

		echo date("Y-m-d h:m:s") . " Création du UUID valide\n";
		$uuid = GeneratorUUID::generate_UUID_array(1)[0];

		echo date("Y-m-d h:m:s") . " Création du log handler et du SurveyRepository\n";
		$logHandler = new LogHandler();
		$surveyRepository = new SurveyRepository(self::$pdo->PDO(), $logHandler);
		$userRepository = new UserRepository(self::$pdo->PDO(), $logHandler);
		$validatorUser = new ValidatorUser();

		echo date("Y-m-d h:m:s") . " Création de l'objet utilisateur et validation de celui-ci\n";

		$resultValidationUser = $validatorUser->validate(self::$mockUser);

		$this->assertEquals(EnumHttpCode::SUCCESS, $resultValidationUser->get_http_code());

		$utilisateur = new User(self::$mockUser);

		echo date("Y-m-d h:m:s") . " Création de l'utilisateur pour le juge\n";

		$nombreLigneAjoute = $userRepository->add_user($utilisateur);

		$this->assertIsInt($nombreLigneAjoute);
		$this->assertNotEquals(0, $nombreLigneAjoute);

		$userFound = $userRepository->get_user_by_email($utilisateur->email);
	}
}