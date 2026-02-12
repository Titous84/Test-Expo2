<?php

namespace Tests\Services\UserService;

use App\Enums\EnumHttpCode;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\TwigService;
use App\Services\UserService;
use App\Handlers\LogHandler;
use App\Services\VerificationCodeService;
use App\Validators\ValidatorJudge;
use App\Validators\ValidatorAdministrator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;
use App\Validators\ValidatorUser;
use App\Validators\ValidatorUserRole;
use App\Validators\ValidatorVerificationCode;
use App\Repositories\VerificationCodeRepository;
use App\Utils\GeneratorUUID;

/**
 * Classe permettant de tester les services avec les services des users.
 * @author Thomas-Gabriel Paquin
 * @package Tests\EvaluationGridService\EvaluationGridServiceTest
 */
final class UserServiceTest extends TestCase {

    private static $pdo;

    private $userService;

    // Valeurs du modèle d'évaluation qui sera envoyer lors de la modification
    private static $mockModify = array(
        "judge" => array(
            "activated" => 1,
            "blacklisted" => 0,
            "categoryId" => 1,
            "email" => "baal@courriel.com",
            "firstName" => "baal",
            "id" => 710,
            "lastName" => "berith",
        )
    );
    
    /**
     * @before
     * Permet de créer une instance de PDO
     * code partiellement généré par Chatgpt
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

        $validatorUserRole = new ValidatorUserRole();
        $validatorUser = new ValidatorUser($logHandler);
        $validatorAdministrator = new ValidatorAdministrator();
        $validatorJudge = new ValidatorJudge($logHandler);
        $emailService = new EmailService(new PHPMailer());
        $twigService = new TwigService();
        $pdo = self::$pdo->pdo();
        $verificationCodeRepository = new VerificationCodeRepository($pdo, $logHandler);

        $validatorVerificationCode = new ValidatorVerificationCode($logHandler);
        $generatorUUID = new GeneratorUUID();
        $verificationCodeService = new VerificationCodeService($verificationCodeRepository, $logHandler, $validatorVerificationCode, $generatorUUID);

        TestingLogger::log("Création d'un modèle de juge");
        $userRepository = new UserRepository(self::$pdo->pdo(), $logHandler);

        TestingLogger::log("Création du service UserService");
        $this->userService = new UserService(
            $userRepository,
            $validatorUserRole,
            $logHandler,
            $validatorUser,
            $validatorAdministrator,
            $validatorJudge,
            $emailService,
            $twigService,
            $verificationCodeService,
        );
    }

    /**
     * @after
     * Permet de détruire l'instance de PDO
     * @return void
     */
    public function tear_down_environment() : void
    {
        self::$pdo = null;
    }

	/**
	 * test_get_judges_fonctionnel
	 * Méthode qui teste le get de tout les juges actifs.
	 */
	public function test_get_judges_fonctionnel()
    {
		TestingLogger::log("Tentative d'obtention de la liste des juges.");
        $blacklisted = false;
        $jugeResult = $this->userService->get_all_judges($blacklisted);

		if ($jugeResult->get_http_code() == EnumHttpCode::SUCCESS) {
            $this->assertNotEmpty($jugeResult->get_content());

        } else {
            $this->fail("Les juges n'ont pas pu être trouvés.");
        }
	}

	/**
	 * test_modify_judge
	 * Méthode qui teste la modification des informations d'un juge avec un mock de modification.
     * Retourne un code 200 si la modification a été effectuée avec succès (retourne un 400 comme si la modification n'a pas fonctionner, mais tout est fonctionnel).
	 */
	public function test_modify_judge(){
		
		TestingLogger::log("Modification des informations d'un juge.");

        $response = $this->userService->update_judge_infos(
			$this::$mockModify,
        );

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_modify_judge");
	}
}