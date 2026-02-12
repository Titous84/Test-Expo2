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

/**
 * Classe permettant de tester la création d'un juge.
 */
final class SignUpJudgeTest extends TestCase
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
     * Fonction de test permettant de tester l'ajout d'un juge avec succès.
     * @return void
     */
    public function test_add_judge_success()
    {
        $_ENV["production"] = "false";
        $judge = array(
            "firstName" => "TestFirstName",
            "lastName" => "TestLastName",
            "pwd" => "TestPwd",
            "email" => "TestSuccess@test.test",
            "picture" => 0,
            "pictureConsent" => 0,
            "activated" => 0,
            "blacklisted" => 0,
            "role_id" => 1,
            "category" => "Sciences physiques",
        );

        $response = $this->userService->add_judge_user($judge);

        // Test
        $this->assertEquals(array("Le juge a été ajouté avec succès."), $response->get_message());
        $this->assertEquals(EnumHttpCode::CREATED, $response->get_http_code());
        $this->assertEquals($judge["email"], $this->userRepository->get_user_by_email($judge["email"])["email"]);
        //Supression de l'utilisateur
        $this->userService->delete_judge($this->userRepository->get_user_by_email($judge["email"])["id"]);  
        $_ENV["production"] = "true";
    }

    /**
     * @author Étienne Nadeau
     * Fonction de test permettant de tester l'ajout d'un juge avec un courriel déjà existant.
     * @return void
     */
    public function test_add_judge_email_exists()
    {
        $_ENV["production"] = "false";
        // Créer un utilisateur existant
        $existingJudgeData = array(
            "firstName" => "Existing",
            "lastName" => "User",
            "pwd" => "oldPwd",
            "email" => "existing.judge@test.test",
            "picture" => 0,
            "pictureConsent" => 0,
            "activated" => 0,
            "blacklisted" => 0,
            "role_id" => 1,
            "category" => "Mathématiques",
        );
        $newJudgeData = array(
            "firstName" => "New",
            "lastName" => "Judge",
            "pwd" => "newPwd",
            "email" => "existing.judge@test.test",
            "picture" => 1,
            "pictureConsent" => 1,
            "activated" => 0,
            "blacklisted" => 0,
            "role_id" => 1,
            "category" => "Informatique",
        );


        $this->userService->add_judge_user($existingJudgeData); // Utiliser une méthode générique pour ajouter un utilisateur
        $response = $this->userService->add_judge_user($newJudgeData);

        // Test de la réponse
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $response->get_http_code());
        // Vérification du message d'erreur
        $this->assertEquals(
            array("L’adresse courriel que vous avez fournie est déjà utilisée."),
            $response->get_message()
        );
        
        $this->assertNull($response->get_content());

        //supression de l'utilisateur existant
        $this->userService->delete_judge($this->userRepository->get_user_by_email($existingJudgeData["email"])["id"]);  
              

        $_ENV["production"] = "true";

    }

    /**
     * @author Étienne Nadeau
     * Fonction de test permettant de tester l'ajout d'un juge avec des données invalides.
     * @return void
     */
    public function test_add_judge_invalid_data()
    {
        $_ENV["production"] = "false";
        $invalidJudgeData = array(
            "firstName" => "", // Nom manquant (supposé être une validation)
            "lastName" => "Invalid",
            "pwd" => "short", // Mot de passe trop court (supposé être une validation)
            "email" => "invalid-email",
            "picture" => 0,
            "pictureConsent" => 0,
            "activated" => 0,
            "blacklisted" => 0,
            "role_id" => 2,
            "category" => "Sciences physiques",
        );

        $response = $this->userService->add_judge_user($invalidJudgeData);

        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $response->get_http_code());

        // Vérification des messages d'erreur
        // Note : Les messages d'erreur peuvent varier en fonction de la logique de validation.
        $this->assertEquals($response->get_message(),["Les champs suivants n'ont pas été remplis : Prénom.",
        "Le courriel est invalide. Le format doit être le suivant : @exemple.ca"]);
        // Vérification que l'utilisateur n'a pas été ajouté
        $this->assertNotEmpty($response->get_message());
        // Vérification que l'utilisateur n'existe pas dans la base de données
        $this->assertFalse($this->userRepository->get_user_by_email($invalidJudgeData["email"]));
        $_ENV["production"] = "true";
    }

 
}