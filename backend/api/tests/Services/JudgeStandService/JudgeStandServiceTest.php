<?php

namespace Tests\Services\JudgeStandServie;

use \App\Enums\EnumHttpCode;
use App\Repositories\JudgeStandRepository;
use App\Services\JudgeStandService;
use App\Handlers\LogHandler;
use \PHPUnit\Framework\TestCase;
use \Symfony\Component\Dotenv\Dotenv;
use \Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester les services de l'assignation des juges.
 * @author XavierHoule
 * @package Tests\JudgeStandService\JudgeStandServiceTest
 */
final class JudgeStandServiceTest extends TestCase {

	private static $pdo;

	private $judgeStandService;

	// Valeurs du modèle d'évaluation qui sera envoyer lors de la création
	private static $mockEvaluation = array(
		"judge_id" => 12,
        "stand_id" => 1,
        "survey_id" => 1,
        "heure" => 5,
	);

	// Valeurs du modèle d'évaluation qui sera envoyer lors de la modification
	private static $mockModify = array(
		"id" => 200,
		"judge_id" => 12,
        "stand_id" => 2,
        "survey_id" => 2,
        "heure" => 5,
	);


	private static $currentTimeSlots = array();

	private static $mockTimeSlots = array(
		"hours" => array(
			["id" => 0, "time" => "9:25:00"],
			["id" => 1, "time" => "9:45:00"],
			["id" => 2, "time" => "10:05:00"],
			["id" => 3, "time" => "10:25:00"],
			["id" => 4, "time" => "10:45:00"],
			["id" => 5, "time" => "11:05:00"],
			["id" => 6, "time" => "11:25:00"],
			["id" => 7, "time" => "11:45:00"]
		)
	);

	/**
	 * @before
	 * Permet de créer une instance de PDO
	 * @return void
	 */
	public function set_up_environment() : void
	{
        //Configuration de l'environnement
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../../.env.prod');
		self::$pdo = new PDOInitialize();


		TestingLogger::log("Changement de la variable ENV en mode développement");
		$_ENV["production"] = "false";

		TestingLogger::log("Création du LogHandler");
		$logHandler = new LogHandler();
		
		TestingLogger::log("Création d'un modèle de plannification");
		$judgeStandRepository = new JudgeStandRepository(self::$pdo->pdo(), $logHandler);

		TestingLogger::log("Création du service JudgeStandService");
		$this->judgeStandService = new JudgeStandService(
			$judgeStandRepository, 
		);
	}

	/** 
	 * test_get_all_judge
	 * Méthode qui teste l'obtention des données de juges
	 * */ 
	public function test_get_all_judge()
    {
        echo date("Y-m-d h:m:s") . " Obtenir tous les juges\n";
        $reponse = $this->judgeStandService->get_judge();

        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());
    }

	/**
	 * test_create_evaluation
	 * Méthode qui teste la création d'un évaluation.
	 */
	public function test_create_evaluation(){
		
		TestingLogger::log("Création du service JudgeStandService");

        $response = $this->judgeStandService->add_evaluation(
			JudgeStandServiceTest::$mockEvaluation,
        );

		$this->assertEquals(EnumHttpCode::CREATED, $response->get_http_code(), "Erreur : test_create_evaluation");

		self::$mockModify["id"] = $response->get_content();
	}


	/**
	 * test_modify_evaluation
	 * Méthode qui teste la modification d'un évaluation.
	 */
	public function test_modify_evaluation(){
		
		TestingLogger::log("Modification du service JudgeStandService");

	
        $response = $this->judgeStandService->update_evaluation(
			JudgeStandServiceTest::$mockModify,
        );

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_modify_evaluation");
	}

	/**
	 * test_delete_evaluation
	 * Méthode qui teste la suppression d'un évaluation.
	 */
	public function test_delete_evaluation(){
		TestingLogger::log("Tentative de suppression d'un évaluation fonctionnel.");
        $judgeStandResult = $this->judgeStandService->delete_evaluation(
            JudgeStandServiceTest::$mockModify["id"],
        );

		if ($judgeStandResult->get_http_code() == EnumHttpCode::SUCCESS) {
            $this->assertTrue($judgeStandResult->get_content());

        } else {
            $this->fail("La suppression n'a pas fonctionner.");
        }
	}

	/** 
	 * test_get_all_judge
	 * Méthode qui teste l'obtention des heures de passages
	 * */ 
	public function test_get_all_time_slots()
    {
        echo date("Y-m-d h:m:s") . " Obtenir tous les juges\n";
        $reponse = $this->judgeStandService->get_time_slots();

		self::$currentTimeSlots = array(
			"hours" => $reponse->get_content()
		);

        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());
    }

	public function test_update_time_slots() 
	{
        $response = $this->judgeStandService->save_time_slots(JudgeStandServiceTest::$mockTimeSlots);

		$this->assertEquals(EnumHttpCode::CREATED, $response->get_http_code(),"Erreur : test_update_time_slots");


		$this->judgeStandService->save_time_slots(self::$currentTimeSlots);
	}
}