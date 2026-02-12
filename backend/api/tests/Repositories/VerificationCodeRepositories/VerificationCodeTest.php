<?php

namespace Repositories\VerificationCodeRepositories;

use App\Handlers\LogHandler;
use App\Models\VerificationCode;
use App\Repositories\VerificationCodeRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Utils\GeneratorUUID;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester le répo des codes de vérification
 * Fortement inspiré de SignUpTeamTest
 * @author Maxime Demers Boucher
 * @package Repositories\VerificationCodeRepositories
 */
final class VerificationCodeTest extends TestCase {

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
	 * test_add_code
	 * Fonction pour tester la génération des codes de vérification.
	 * @author Maxime Demers Boucher
	 * @return void
	 */
	public function test_add_code(){
		$logHandler = new LogHandler();
		
		TestingLogger::log(" Création du répertoire VerificationCodeRepositorier");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log(" Création du répertoire genrateurUUID");
		$generatorUUID = new GeneratorUUID();

		$UUID = $generatorUUID->generate_single_UUID();
		$verificationCode =substr($UUID,24);
		$code = new VerificationCode($verificationCode,"CODETOVALIDATE",date('c',(time()+900)));
        $responseAjout = $verificationCodeRepository->add_code($code);

		$this->assertEquals($verificationCode,$responseAjout,"Erreur : test_add_code");
	}

	/**
	 * test_delete_code
	 * Fonction qui permet de tester la suppresion d'un code de validation
	 * @return void
	 */
	public function test_delete_code(){
		$logHandler = new LogHandler();

		TestingLogger::log(" Création du répertoire VerificationCodeRepositorier");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log(" Création du répertoire genrateurUUID");
		$generatorUUID = new GeneratorUUID();

		$verificationCode = $generatorUUID->generate_single_UUID();
		$code = new VerificationCode(substr($verificationCode,24),"CODETODELETE",date('c',(time()+900)));
        $responseAjout = $verificationCodeRepository->add_code($code);

		$responseDelete = $verificationCodeRepository->delete_code("CODETODELETE");

			$this->assertGreaterThan(0,$responseDelete, "Erreur : test_delete_code");
	}
	
	/**
	 * test_validate_code
	 * Fonction qui permet de tester la validation d'un code de vérification
	 * @return void
	 */
	public function test_validate_code(){
		$logHandler = new LogHandler();

		TestingLogger::log(" Création du répertoire VerificationCodeRepositorier");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log(" Création du répertoire genrateurUUID");
		$generatorUUID = new GeneratorUUID();

		$UUID = $generatorUUID->generate_single_UUID();
		$verificationCode =substr($UUID,24);
		$code = new VerificationCode($verificationCode,"CODETOVALIDATE",date('c',(time()+900)));
        $responseAjout = $verificationCodeRepository->add_code($code);
		
		$responseValidate = $verificationCodeRepository->validate_code($verificationCode);

		$this->assertEquals("CODETOVALIDATE",$responseValidate,"Erreur : test_validate_code");
	}

	/**
	 * test_validate_code_not_in_db
	 * Fonction qui permet de tester le result de validate_code avec un code non présent
	 * @return void
	 */
	public function test_validate_code_not_in_db(){
		$logHandler = new LogHandler();

		TestingLogger::log(" Création du répertoire VerificationCodeRepositorier");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log(" Création du répertoire genrateurUUID");
		$generatorUUID = new GeneratorUUID();

		$UUID = $generatorUUID->generate_single_UUID();
		$verificationCode =substr($UUID,24);
		$code = new VerificationCode($verificationCode,"CODETOVALIDATE",date('c',(time()+900)));
        $responseAjout = $verificationCodeRepository->add_code($code);
		
		$responseValidate = $verificationCodeRepository->validate_code("INVALIDECODE");

		$this->assertEquals(null,$responseValidate,"Erreur : test_validate_code");
	}

		/**
	 * test_validate_code_expired
	 * Fonction qui permet de tester le result de validate_code avec un code expiré
	 * @return void
	 */
	public function test_validate_code_expired(){
		$logHandler = new LogHandler();

		TestingLogger::log("Création du répertoire VerificationCodeRepository");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->PDO(), $logHandler);

		TestingLogger::log("Création du répertoire genrateurUUID");
		$generatorUUID = new GeneratorUUID();

		$UUID = $generatorUUID->generate_single_UUID();
		$verificationCode =substr($UUID,24);
		$code = new VerificationCode($verificationCode,"CODETOVALIDATE",date('c',time()));
        $responseAjout = $verificationCodeRepository->add_code($code);
		
		$responseValidate = $verificationCodeRepository->validate_code("CODETOVALIDATE");

		$this->assertEquals(null,$responseValidate,"Erreur : test_validate_code");
	}
}
