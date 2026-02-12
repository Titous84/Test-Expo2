<?php

namespace Services\SignUpCategoryService;

use App\Handlers\LogHandler;
use App\Repositories\SignUpCategoryRepository;
use App\Services\SignUpCategoryService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Test\TestsUtils\PDOInitialize;

/**
 * SignUpcategoryTest
 * @author Tristan Lafontaine
 */
final class SignUpcategoryTest extends TestCase{

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

    public function test_get_category(){
        
        echo date("Y-m-d h:m:s") . " Création du répertoire SignUpCategoryRepository\n";
        $logHandler = new LogHandler();
        $signUpCategoryRepository = new SignUpCategoryRepository(self::$pdo->PDO(), $logHandler);

        echo date("Y-m-d h:m:s") . " Initilisation du service SignUpCategoryService\n";
        $signUpCategoryService = new SignUpCategoryService($signUpCategoryRepository);

        echo date("Y-m-d h:m:s") . " Obtenir les categories de la base de données\n";
        $response = $signUpCategoryService->get_all_category();

        $this->assertGreaterThan(9, sizeof($response->get_content()));
        $this->assertEquals(200, $response->get_http_code());
        $this->assertIsObject($response);
        $this->assertEquals("categories", $response->get_message()[0]);
    }
}