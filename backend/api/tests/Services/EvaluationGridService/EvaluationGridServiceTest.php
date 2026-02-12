<?php

namespace Tests\Services\EvaluationGridService;

use App\Enums\EnumHttpCode;
use App\Repositories\EvaluationGridRepository;
use App\Services\EvaluationGridService;
use App\Handlers\LogHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester les services avec les evaluations grid.
 * @author Thomas-Gabriel Paquin
 * @package Tests\EvaluationGridService\EvaluationGridServiceTest
 */
final class EvaluationGridServiceTest extends TestCase {

	private static $pdo;

	private $evaluationGridService;

	// Valeurs du modèle d'évaluation qui sera envoyer lors de la création
	private static $mockGrid = array(
		"name" => "patate",
		"rating_section" =>[[
			"name" => "salut",
			"position" => 1,
			"criterias" => [
				[				
					"incremental_value" => 1,
					"max_value" => 10,
					"name" => "critere",
					"position" => 1,
				]
			]],
		],	
	);
	// Valeurs du modèle d'évaluation qui sera envoyer lors de la modification
	private static $mockModify = array(
		"id" => 4,
		"name" => "Modification",
		"rating_section" =>[[
			"name" => "Modifier",
			"position" => 1,
			"criterias" => [
				[
					"incremental_value" => 1,
					"max_value" => 5,
					"name" => "moded",
					"position" => 1,
				]
			]],
		],	
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

		TestingLogger::log("Création d'un modèle EvaluationGrid");
		$evaluationGridRepository = new EvaluationGridRepository(self::$pdo->pdo(), $logHandler);

		TestingLogger::log("Création du service EvaluationGridService");
		$this->evaluationGridService = new EvaluationGridService(
			$evaluationGridRepository, 
			$logHandler, 
		);
	}

	/**
	 * test_get_evaluationGrid_with_empty_id
	 * Méthode qui teste si le EvaluationGridService se gère bien sans id.
	 */
	public function test_get_evaluationGrid_with_empty_id()
    {
		TestingLogger::log("Tentative d'obtention d'un modèle d'évaluation sans id.");
        $evaluationGridResult = $this->evaluationGridService->getEvaluationGridById("");

		TestingLogger::log(json_encode($evaluationGridResult->get_content()));
		
		$this->assertEquals(EnumHttpCode::BAD_REQUEST, $evaluationGridResult->get_http_code());
		$this->assertEquals(array("La grille d'évaluation n'existe pas"), $evaluationGridResult->get_message());
		$this->assertEmpty($evaluationGridResult->get_content());
	}

    /**
     * test_get_evaluationGrid_with_invalid_id
     * Méthode qui teste si le EvaluationGridService se gère bien avec un id invalide.
     */
    public function test_get_evaluationGrid_with_invalid_id()
    {
        TestingLogger::log("Tentative d'obtention d'un modèle d'évaluation avec un id invalide.");
        $evaluationGridResult = $this->evaluationGridService->getEvaluationGridById("123");

        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $evaluationGridResult->get_http_code());
        $this->assertEquals(array("La grille d'évaluation n'existe pas"), $evaluationGridResult->get_message());
        $this->assertEmpty($evaluationGridResult->get_content());
    }

	/**
	 * test_get_evaluationGrid
	 * Méthode qui teste le get des evaluationGrid fonctionne bien.
	 */
	public function test_get_evaluationGrid_fonctionnel()
    {
		TestingLogger::log("Generation d'un ID");
		$id = 3;

		TestingLogger::log("Tentative d'obtention du modèle d'évaluation fonctionnel.");
        $evaluationGridResult = $this->evaluationGridService->getEvaluationGridById($id);

		if ($evaluationGridResult->get_http_code() == EnumHttpCode::SUCCESS) {
            $this->assertNotEmpty($evaluationGridResult->get_content());

        } else {
            $this->fail("Le modèle d'évaluation n'a pas été trouvé.");
        }
	}

	/**
	 * test_create_evaluationGrid
	 * Méthode qui teste la création d'un modèle d'évaluation.
	 */
	public function test_create_evaluationGrid(){
		
		TestingLogger::log("Création du service EvaluationGridService");

        $response = $this->evaluationGridService->insertEvaluationGrid(
			EvaluationGridServiceTest::$mockGrid,
        );

		$this->assertEquals(EnumHttpCode::CREATED,$response->get_http_code(),"Erreur : test_create_evaluationGrid");
	}

	/**
	 * test_modify_evaluationGrid
	 * Méthode qui teste la modification d'un modèle d'évaluation.
	 */
	public function test_modify_evaluationGrid(){
		
		TestingLogger::log("Modification du service EvaluationGridService");

        $response = $this->evaluationGridService->updateEvaluationGrid(
			EvaluationGridServiceTest::$mockModify,
        );

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_modify_evaluationGrid");
	}

	/**
	 * test_delete_evaluationGrid
	 * Méthode qui teste la suppression d'un modèle d'évaluation.
	 */
	public function test_delete_evaluationGrid(){

		TestingLogger::log("Generation d'un ID");
		$id = EvaluationGridServiceTest::$mockModify["id"];

		TestingLogger::log("Tentative de suppression d'un modèle d'évaluation fonctionnel.");
        $evaluationGridResult = $this->evaluationGridService->deleteEvaluationGridById($id);

		if ($evaluationGridResult->get_http_code() == EnumHttpCode::SUCCESS) {
            $this->assertNull($evaluationGridResult->get_content());

        } else {
            $this->fail("La suppression n'a pas fonctionner.");
        }
	}
}