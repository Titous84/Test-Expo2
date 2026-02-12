<?php

namespace Tests\Services\SignUpTeamService;

use App\Enums\EnumHttpCode;
use App\Fabricators\Emails\EmailEvaluationFabricator;
use App\Repositories\SurveyRepository;
use App\Services\SurveyService;
use App\Handlers\LogHandler;
use App\Models\Judge;
use App\Services\EmailService;
use App\Services\TwigService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Utils\GeneratorUUID;
use App\Validators\ValidatorQuestionResult;
use App\Validators\ValidatorUUID;
use PHPMailer\PHPMailer\PHPMailer;
use Test\TestsUtils\PDOInitialize;
use App\Models\User;
use App\Repositories\JudgeStandRepository;
use App\Repositories\UserRepository;
use App\Validators\ValidatorCommentResult;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester les formulaires des juges.
 * @author Christopher Boisvert
 * @author Jean-Christophe Demers
 * @package Tests\SurveyService\SurveyServiceTest
 */
final class SurveyServiceTest extends TestCase
{

	private static $pdo;

	private $surveyService;

	private static $mockUser = array(
		"first_name" => "John",
		"last_name" => "Doe",
		"username" => "christolord3",
		"pwd" => "123elite",
		"email" => "johndoe@hotmail.com",
		"picture_consent" => 1,
		"activated" => 1,
		"blacklisted" => 0,
		"role_id" => 1
	);

	private static $mockJudge = array(
		"category" => "Humain",
		"firstName" => "John",
		"lastName" => "Doe",
		"username" => "christolord3",
		"pwd" => "123elite",
		"email" => "johndoe@hotmail.com",
		"pictureConsent" => 1,
		"activated" => 1,
		"blacklisted" => 0,
		"role_id" => 1
	);

	private static $mockEvaluation = array(
		'jugeId' => 23,
		'standId' => 1,
		'surveyId' => 2,
		'heure' => 2,
	);

	private static $evaluation_id = null;

	private static $judgeUUID = null;

	// criteria_id => score
	private static $mockScores = [
		31 => 1,
		32 => 6,
		33 => 2,
		34 => 7,
		35 => 9,
		36 => 0,
		37 => 8,
	];

	/**
	 * @before
	 * Permet de créer une instance de PDO
	 * @return void
	 */
	public function set_up_environment(): void
	{
		//Configuration de l'environnement
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../../.env.prod');
		self::$pdo = new PDOInitialize();


		TestingLogger::log("Changement de la variable ENV en mode développement");
		$_ENV["production"] = "false";

		TestingLogger::log("Création du LogHandler");
		$logHandler = new LogHandler();

		TestingLogger::log("Création du SurveyRepository");
		$surveyRepository = new SurveyRepository(self::$pdo->pdo(), $logHandler);

		TestingLogger::log("Création du ValidatorUUID");
		$validatorUUID = new ValidatorUUID();

		TestingLogger::log("Création du ValidatorCommentResult");
		$validatorCommentResult = new ValidatorCommentResult();

		TestingLogger::log("Création du ValidatorQuestionResult");
		$validatorQuestionResult = new ValidatorQuestionResult();

		TestingLogger::log("Création du PHPMailer");
		$phpMailer = new PHPMailer();

		TestingLogger::log("Création du EmailService");
		$emailService = new EmailService($phpMailer);

		TestingLogger::log("Création du TwigService");
		$twigService = new TwigService();

		TestingLogger::log("Création du EmailEvaluationFabricator");
		$emailEvaluationFabricator = new EmailEvaluationFabricator($emailService, $twigService);

		TestingLogger::log("Création du service SurveyService");
		$this->surveyService = new SurveyService(
			$surveyRepository,
			$logHandler,
			$validatorUUID,
			$validatorCommentResult,
			$validatorQuestionResult,
			$emailEvaluationFabricator
		);
	}




	/**
	 * test_get_survey_with_empty_uuid
	 * Méthode qui teste si le SurveyService gère bien les uuids vide.
	 */
	public function test_get_survey_with_empty_uuid()
	{
		TestingLogger::log("Tentative d'obtention du Survey");
		$surveyResult = $this->surveyService->get_all_survey_by_judge_id("");

		$this->assertEquals($surveyResult->get_http_code(), EnumHttpCode::BAD_REQUEST);
		$this->assertEquals(array("L'UUID se doit d'être de 36 caractères."), $surveyResult->get_message());
		$this->assertEmpty($surveyResult->get_content());
	}

	/**
	 * test_get_survey_with_empty_uuid
	 * Méthode qui teste si le SurveyService gère bien les uuids vide.
	 */
	public function test_get_survey_with_random_uuid()
	{
		TestingLogger::log("Generation d'un UUID");
		$randomUUID = GeneratorUUID::generate_UUID_array(1);

		TestingLogger::log("Tentative d'obtention du Survey");
		$surveyResult = $this->surveyService->get_all_survey_by_judge_id($randomUUID[0]);

		$this->assertEquals(EnumHttpCode::NOT_FOUND, $surveyResult->get_http_code());
		$this->assertEquals(array("Aucune évaluation trouvé."), $surveyResult->get_message());
		$this->assertEmpty($surveyResult->get_content());
	}

	/**
	 * Fonction permettant de tester les résultats quand un juge existe et qu'il n'a pas d'évaluation.
	 */
	public function test_get_survey_with_good_uuid_but_no_survey()
	{
		TestingLogger::log("Création d'un utilisateur");
		$user = new User(self::$mockUser);

		TestingLogger::log("Création du UserRepository");
		$userRepository = new UserRepository(self::$pdo->PDO(), new LogHandler());

		TestingLogger::log("Ajout de l'utilisateur");
		$userRepository->add_user($user);

		TestingLogger::log("Création du Judge");
		$judge = new Judge(self::$mockJudge);

		TestingLogger::log("Ajout du juge");
		$jugeUUID = $userRepository->add_judge_judge($judge);

		$this->assertEquals("string", gettype($jugeUUID));
		self::$judgeUUID = $jugeUUID;

		TestingLogger::log("Tentative d'obtention du Survey");
		$surveyResult = $this->surveyService->get_all_survey_by_judge_id(self::$judgeUUID);

		$this->assertEquals(EnumHttpCode::NOT_FOUND, $surveyResult->get_http_code());
		$this->assertEquals(array("Aucune évaluation trouvé."), $surveyResult->get_message());
		$this->assertEmpty($surveyResult->get_content());
	}

	public function test_assign_judge()
	{
		TestingLogger::log("Création du JudgeStandRepository");
		$judgeStandRepository = new JudgeStandRepository(self::$pdo->PDO());

		$evaluation = $judgeStandRepository->add_evaluation(self::$mockEvaluation);
		$this->assertTrue($evaluation);

		TestingLogger::log("Tentative d'obtention du Survey");
		$this->assertNotNull(self::$judgeUUID);
		$surveyResult = $this->surveyService->get_all_survey_by_judge_id(self::$judgeUUID);

		$this->assertEquals(EnumHttpCode::SUCCESS, $surveyResult->get_http_code());
		$this->assertEquals(array("Nous avons trouvés des formulaires"), $surveyResult->get_message());
		$evaluation = $surveyResult->get_content();
		$this->assertEquals(1, count($evaluation));
		self::$evaluation_id = $evaluation[0]["id"];
	}

	public function test_judge_fill_evaluation()
	{
		TestingLogger::log("Tentative d'évaluation du juge.");
		$this->assertNotNull(self::$evaluation_id);
		foreach (self::$mockScores as $criteria_id => $score) {
			$result_question = $this->surveyService->set_question_result([
				"score" => 10 - $score,
				"evaluation_id" => self::$evaluation_id,
				"criteria_id" => $criteria_id,
			]);
			$this->assertEquals(EnumHttpCode::SUCCESS, $result_question->get_http_code());
			$this->assertEquals(array("Le résultat de cette question a bel et bien été mis à jour !"), $result_question->get_message());
			$this->assertNull($result_question->get_content());
		}
		
		TestingLogger::log("Deuxiéme Tentative d'évaluation du juge.");
		$global_score = 0;
		foreach (self::$mockScores as $criteria_id => $score) {
			$global_score += $score;
			$result_question = $this->surveyService->set_question_result([
				"score" => $score,
				"evaluation_id" => self::$evaluation_id,
				"criteria_id" => $criteria_id,
			]);
			$this->assertEquals(EnumHttpCode::SUCCESS, $result_question->get_http_code());
			$this->assertEquals(array("Le résultat de cette question a bel et bien été mis à jour !"), $result_question->get_message());
			$this->assertNull($result_question->get_content());
		}

		
		TestingLogger::log("Tentative d'écriture de commentaire d'évaluation du juge.");
		$result_commentaire = $this->surveyService->set_comment_result([
			"comment" => "WOW... Bravo les champions...",
			"evaluation_id" => self::$evaluation_id,
		]);
		$this->assertEquals(EnumHttpCode::SUCCESS, $result_commentaire->get_http_code());
		$this->assertEquals(array("Le résultat de cette question a bel et bien été mis à jour !"), $result_commentaire->get_message());
		$this->assertEquals(true, $result_commentaire->get_content());

		TestingLogger::log("Vérification du score d'évaluation du juge.");
		$score = $this->surveyService->get_survey_score(["surveyId" => self::$evaluation_id]);
		$this->assertEquals(EnumHttpCode::SUCCESS, $score->get_http_code());
		$this->assertEquals(array("Le score a été obtenu avec succès."), $score->get_message());
		$this->assertNotNull($score->get_content());
		$this->assertEquals($global_score, $score->get_content()["score"]);

		TestingLogger::log("Fermeture de l'évaluation du juge.");
		$close_result = $this->surveyService->close_survey(["surveyId" => self::$evaluation_id]);
		$this->assertEquals(EnumHttpCode::SUCCESS, $close_result->get_http_code());
		$this->assertEquals(array("Le formulaire d'évaluation a été fermé avec succès."), $close_result->get_message());
		$this->assertNull($close_result->get_content());
	}
}