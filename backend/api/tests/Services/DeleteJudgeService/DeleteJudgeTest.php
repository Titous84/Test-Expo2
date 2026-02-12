<?php
namespace Services\SignUpJudgeService;

use \App\Enums\EnumHttpCode;
use App\Repositories\VerificationCodeRepository;
use App\Services\EmailService;
use App\Services\TwigService;
use \App\Services\UserService;
use App\Repositories\UserRepository;
use App\Services\VerificationCodeService;
use App\Utils\GeneratorUUID;
use App\Validators\ValidatorVerificationCode;
use PHPMailer\PHPMailer\PHPMailer;
use \PHPUnit\Framework\TestCase;
use \Symfony\Component\Dotenv\Dotenv;
use \Test\TestsUtils\PDOInitialize;
use \App\Validators\ValidatorJudge;
use \App\Validators\ValidatorUser;
use \App\Validators\ValidatorAdministrator;
use App\Handlers\LogHandler;
use \App\Validators\ValidatorUserRole;
use App\Exceptions\EntityNotFoundException;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

/**
 * Classe permettant de tester la suppression d'un juge.
 */
final class DeleteJudgeTest extends TestCase
{

    private $pdo;
    private $userRepository;
    private $userService;

    
    /**
     * @before
     */
  public function set_up_environement()
    {
        //Configuration de l'environnement
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../.env.prod');
        $this->pdo = new PDOInitialize();

        $logHandler = new LogHandler();

        $this->userRepository = new UserRepository($this->pdo->PDO(), $logHandler);
        $this->userService = new UserService(
            $this->userRepository,
            new ValidatorUserRole(), 
            $logHandler, 
            new ValidatorUser(),
            new ValidatorAdministrator(),
            new ValidatorJudge(),
            new EmailService(new PHPMailer),
            new TwigService(),
            new VerificationCodeService(
                new VerificationCodeRepository($this->pdo->PDO(), $logHandler),
                $logHandler,
                new ValidatorVerificationCode(),
                new GeneratorUUID(),
            ),
        );
    }



    /**
     * @author Étienne Nadeau
     * Fonction de test permettant de tester la suppression d'un juge.
     * @return void
     */
    public function test_delete_judge(){
         $_ENV["production"] = "false";
        $this->pdo = new PDOInitialize();
        $judge = array(
            "firstName" => "TestFirstName",
            "lastName" => "TestLastName",
            "pwd" => "TestPwd",
            "email" => "TestEmail11@test.test",
            "picture" => 0,
            "pictureConsent" => 0,
            "activated" => 0,
            "blacklisted" => 0,
            "role_id" => 2,
            "category" => "Sciences physiques",
        );

        $this->userService->add_judge_user($judge);
        $userID = $this->userRepository->get_user_by_email($judge["email"])["id"];
        $response = $this->userService->delete_judge($userID);

        $this->assertEquals(array("Le juge a été supprimé avec succès."), $response->get_message());
        $this->assertEquals(EnumHttpCode::SUCCESS, $response->get_http_code());
        
        $_ENV["production"] = "true";
        

    
    }
    /**
     * @author Étienne Nadeau
     * fonction de test permettant de tester la suppression d'un juge inexistant.
     * @return void
     */
    public function test_delete_judge_not_found()
    {
        $_ENV["production"] = "false";
        $userIDInexistant = 99999; 
        
        $response = $this->userService->delete_judge($userIDInexistant);
        $this->assertSame(array("le juge dont l'id est " . $userIDInexistant . " n'a pas été bel et bien été supprimé"), $response->get_message());
        $_ENV["production"] = "true";
    }
    /**
    * @author Étienne Nadeau
    * fonction de test permettant de tester la suppression d'un juge avec un id négatif.
    * @return void
    */
    public function test_delete_judge_negative_id()
    {
        $_ENV["production"] = "false";
        $invalidUserId = -1; 

        $reponse = $this->userService->delete_judge( $invalidUserId);
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $reponse->get_http_code());
        $this->assertEquals(array("L'identifiant se doit d'être un chiffre positif."), $reponse->get_message());
        $this->assertTrue(true, "L'identifiant se doit d'être un chiffre positif.");

        $_ENV["production"] = "true";
    }
}
