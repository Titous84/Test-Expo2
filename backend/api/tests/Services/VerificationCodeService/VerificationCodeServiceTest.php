<?php

namespace Tests\Services\VerificationCodeServiceTest;

use App\Enums\EnumHttpCode;
use App\Repositories\VerificationCodeRepository;
use App\Services\VerificationCodeService;
use App\Handlers\LogHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use App\Utils\GeneratorUUID;
use App\Validators\ValidatorVerificationCode;
use PHPMailer\PHPMailer\PHPMailer;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester le service des codes de vérification
 * Fortement inspiré de SurveyServiceTest
 * @author Maxime Demers Boucher
 * @package Tests\VerificationCodeService\VerificationCodeServiceTest
 */
final class VerificationCodeServiceTest extends TestCase {

	private static $pdo;

	private $verificationCodeService;

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

		TestingLogger::log("Création du SurveyRepository");
		$verificationCodeRepository = new VerificationCodeRepository(self::$pdo->pdo(), $logHandler);

		TestingLogger::log("Création du PHPMailer");
		$phpMailer = new PHPMailer();

		TestingLogger::log("Création du PHPMailer");
		$validatorVerificationCode = new ValidatorVerificationCode();

		TestingLogger::log("Création du PHPMailer");
		$phpMailer = new PHPMailer();

		TestingLogger::log("Création du PHPMailer");
		$generatorUUID = new GeneratorUUID();

		TestingLogger::log("Création du service VerificationCodeService");
		$this->verificationCodeService = new VerificationCodeService(
			$verificationCodeRepository,
			$logHandler,
			$validatorVerificationCode,
			$generatorUUID
		);
	}


	
	
	/**
	 * test_generate_code
	 * Méthode qui teste si le VerificationService génére bien les code de vérification
	 */
	public function test_generate_code()
    {
		TestingLogger::log("Tentative de génération du code");
        $verificationCodeResult = $this->verificationCodeService->generate_code("email@gmail.com");

		$this->assertEquals( EnumHttpCode::SUCCESS,$verificationCodeResult->get_http_code(), "Erreur : test_generate_code_HTTP_CODE");
		$this->assertNotEmpty($verificationCodeResult->get_content(),  "Erreur : test_generate_code_RETURN_NULL");
	}

	/**
	 * test_verify_code
	 * Méthode qui vérifie un code de validation
	 */
	public function test_verify_code()
    {
		TestingLogger::log("Tentative de génération du code");
        $verificationCodeResult = $this->verificationCodeService->generate_code("email@gmail.com");
		
		$contenu = $verificationCodeResult->get_content();
		TestingLogger::log($contenu);


        $ValidationResult = $this->verificationCodeService->verify_code($contenu);

		$this->assertEquals(EnumHttpCode::SUCCESS,$ValidationResult->get_http_code(), "Erreur :  test_verify_code_HTTP_CODE");
		$this->assertNotEmpty($ValidationResult->get_content(), "Erreur :  test_verify_code_");
	}

	/**
	 * test_delete_code
	 * Méthode qui delete le code de vérification
	 */
	public function test_delete_code()
    {
		TestingLogger::log("Tentative de génération du code");
        $verificationCodeResult = $this->verificationCodeService->generate_code("email@gmail.com");
		
        $deleteResult = $this->verificationCodeService->delete_code("email@gmail.com");

		$this->assertEquals(EnumHttpCode::SUCCESS,$deleteResult->get_http_code(), "Erreur :  test_verify_code_HTTP_CODE");
		$this->assertGreaterThan(0,$deleteResult->get_content(), "Erreur :  test_verify_code_RETURN_0");
	}
}