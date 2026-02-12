<?php

namespace Tests\Services\UserServiceAdministrators;

use App\Enums\EnumHttpCode;
use App\Repositories\UserRepository;
use App\Services\TwigService;
use App\Services\UserService;
use App\Handlers\LogHandler;
use App\Validators\ValidatorAdministrator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;
use Exception;
// Dépendances de UserService
use App\Validators\ValidatorUserRole;
use App\Validators\ValidatorUser;
use App\Validators\ValidatorJudge;
use App\Services\EmailService;
use PHPMailer\PHPMailer\PHPMailer;
use App\Validators\ValidatorVerificationCode;
use App\Repositories\VerificationCodeRepository;
use App\Services\VerificationCodeService;
use App\Utils\GeneratorUUID;

/**
 * Tests sur le service des administrateurs.
 * @author Antoine Ouellette
 * @package Tests\Services\UserServiceAdministrators
 */
final class UserServiceTest extends TestCase {

    private static $pdo;

    private $userService;
    
    /**
     * @before
     * Configuration de l'environnement de test avant chaque test.
     * Crée une instance de PDO (pour la BD).
     * @return void
     */
    public function set_up_environment() : void
    {
        // Récupération des variables d'environnement.
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../.env.prod');

        // TestingLogger affiche des logs pour les tests.
        TestingLogger::log("Changement de la variable ENV en mode développement");
        $_ENV["production"] = "false";
        // LogHandler est le gestionnaire de logs de l'API.
        TestingLogger::log("Création du LogHandler");
        $logHandler = new LogHandler();
        
        // Instancier la classe de validation des administrateurs reçus par l'API.
        $validatorAdministrator = new ValidatorAdministrator();
        
        // Création du PDO (sera utilisé pour les requêtes à la BD).
        self::$pdo = new PDOInitialize();
        // Initialise la connexion avec la DB.
        self::$pdo = self::$pdo->pdo();

        // Création de UserService qui sera utilisé dans les tests.
        TestingLogger::log("Création du service UserService");
        $this->userService = new UserService(
            new UserRepository(self::$pdo, $logHandler),
            new ValidatorUserRole(),
            $logHandler,
            new ValidatorUser($logHandler),
            $validatorAdministrator,
            new ValidatorJudge($logHandler),
            new EmailService(new PHPMailer()),
            new TwigService(),
            new VerificationCodeService(
                new VerificationCodeRepository(self::$pdo, $logHandler),
                $logHandler,
                new ValidatorVerificationCode($logHandler),
                new GeneratorUUID()
            ),
        );
    }

    /**
     * @after
     * Nettoyage après chaque test.
     * Efface le PDO.
     * @return void
     */
    public function tear_down_environment() : void
    {
        self::$pdo = null;
    }

    /**
     * Test de la création d'un administrateur valide.
     * - Le courriel n'est pas vide, a un format valide et n'est pas déjà utilisé.
     * - Le mot de passe n'est pas vide.
     * @author Antoine Ouellette
     */
    public function test_create_valid_administrator()
    {
        TestingLogger::log("Tentative de création de l'administrateur valide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "email@valide.com", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test qu'un message de succès a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["L'administrateur a été créé avec succès."], $serviceResponseMessage, "L'API n'a pas retourné le message de succès attendu.");

        // Test que le code de status HTTP est 200.
        $this->assertEquals(EnumHttpCode::CREATED, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 200.");
    }

    /**
     * Test de la création d'un administrateur invalide.
     * - Le courriel est vide.
     * - Le mot de passe n'est pas vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_empty_email()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Le corps de la requête est invalide."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Test de la création d'un administrateur invalide.
     * - Le courriel n'est pas vide et a un format valide.
     * - Le mot de passe est vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_empty_password()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "administrateur@motdepassevide.com", "password" => ""]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Le corps de la requête est invalide."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Test de la création d'un administrateur invalide.
     * - Le courriel est vide.
     * - Le mot de passe est vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_empty_email_password()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "", "password" => ""]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Le corps de la requête est invalide."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Test de la création d'un administrateur avec un courriel invalide.
     * - Le courriel n'est pas vide, mais a un format invalide.
     * - Le mot de passe n'est pas vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_invalid_email()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "pasuncourriel", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Le corps de la requête est invalide."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Test de la création d'un administrateur avec un mot de passe invalide.
     * - Le courriel n'est pas vide, mais est composé seulement d'espaces.
     * - Le mot de passe n'est pas vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_invalid_password()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création de l'administrateur.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "     ", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Le corps de la requête est invalide."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Test de la création d'un administrateur avec un courriel déjà utilisé.
     * - Le courriel n'est pas vide, a un format valide, mais est déjà utilisé.
     * - Le mot de passe n'est pas vide.
     * @author Antoine Ouellette
     */
    public function test_create_administrator_email_already_taken()
    {
        TestingLogger::log("Tentative de création de l'administrateur invalide.");

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Création d'un premier administrateur valide.
            $this->userService->create_administrator(
                ["email" => "deja@utilise.com", "password" => "5^EqyUu,k2]1"]
            );

            // Création d'un administrateur avec le même courriel.
            $serviceResponse = $this->userService->create_administrator(
                ["email" => "deja@utilise.com", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertCount(1, $serviceResponseMessage, "Il n'y a pas 1 message dans la réponse de l'API.");
        $this->assertEquals(["Un administrateur avec cet email existe déjà."], $serviceResponseMessage, "L'API n'a pas retourné le message d'erreur attendu.");

        // Test que le code de status HTTP est 400.
        $this->assertEquals(EnumHttpCode::BAD_REQUEST, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 400.");
    }

    /**
     * Teste lorsque le repository de l'administrateur ne réussit pas à créer l'administrateur.
     * (Il y a une erreur dans le UserRepository et il retourne false).
     * Doit retourner un code 500 et le message d'erreur:
     * "Une erreur inattendue est survenue lors de la création de l’administrateur."
     * @author Antoine Ouellette
     */
    public function test_create_administrator_repository_fail()
    {
        // On va override le UserRepository avec une classe qui simule une erreur.
        $corruptedUserRepository = $this->createMock(UserRepository::class);
        // Forcer la méthode create_administrator() à retourner false pour simuler une erreur.
        $corruptedUserRepository->method('create_administrator')->willReturn(false);

        // Dépendances de UserService réutilisées.
        $logHandler = new LogHandler();
        $validatorAdministrator = new ValidatorAdministrator();

        // Créer un UserService en utilisant le UserRepository corrompu.
        $userService = new UserService(
            $corruptedUserRepository,
            new ValidatorUserRole(),
            $logHandler,
            new ValidatorUser($logHandler),
            $validatorAdministrator,
            new ValidatorJudge($logHandler),
            new EmailService(new PHPMailer()),
            new TwigService(),
            new VerificationCodeService(
                new VerificationCodeRepository(self::$pdo, $logHandler),
                $logHandler,
                new ValidatorVerificationCode($logHandler),
                new GeneratorUUID()
            ),
        );

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Tentative de création d'un administrateur.
            $serviceResponse = $userService->create_administrator(
                ["email" => "test@erreurcreation.com", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertEquals(
            ["Une erreur inattendue est survenue lors de la création de l'administrateur."],
            $serviceResponseMessage,
            "L'API n'a pas retourné le message d'erreur attendu."
        );

        // Test que le code de statut HTTP est 500.
        $this->assertEquals(EnumHttpCode::SERVER_ERROR, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 500.");
    }

    /**
     * Test lorsqu'une erreur survient du côté serveur pour le code de création d'un administrateur.
     * Doit retourner un code 500 et le message d'erreur:
     * "Une erreur inattendue est survenue lors de la création de l'administrateur."
     * @author Antoine Ouellette
     */
    public function test_create_administrator_server_error()
    {
        // On va override le UserRepository avec une classe qui simule une erreur.
        $corruptedUserRepository = $this->createMock(UserRepository::class);
        // Forcer la méthode create_administrator() à lancer une exception.
        $corruptedUserRepository->method('create_administrator')->willThrowException(new Exception("Erreur côté serveur simulée."));

        // Dépendances de UserService réutilisées.
        $logHandler = new LogHandler();
        $validatorAdministrator = new ValidatorAdministrator();

        // Créer un UserService en utilisant le UserRepository corrompu.
        $userService = new UserService(
            $corruptedUserRepository,
            new ValidatorUserRole(),
            $logHandler,
            new ValidatorUser($logHandler),
            $validatorAdministrator,
            new ValidatorJudge($logHandler),
            new EmailService(new PHPMailer()),
            new TwigService(),
            new VerificationCodeService(
                new VerificationCodeRepository(self::$pdo, $logHandler),
                $logHandler,
                new ValidatorVerificationCode($logHandler),
                new GeneratorUUID()
            ),
        );

        // Déclaration des variables.
        $serviceResponse = null;
        $serviceResponseMessage = null;
        $httpStatusCode = null;

        try
        {
            // Tentative de création d'un administrateur.
            $serviceResponse = $userService->create_administrator(
                ["email" => "test@erreurserveur.com", "password" => "5^EqyUu,k2]1"]
            );

            // le code est un int.
            $httpStatusCode = $serviceResponse->get_http_code();
            // le message est un tableau.
            $serviceResponseMessage = $serviceResponse->get_message();
        }
        catch (Exception $exception)
        {
            $this->fail("Une erreur a été lançée lors de l'exécution du test: " . $exception);
        }

        // Test que le message d'erreur a été retourné.
        $this->assertEquals(
            ["Une erreur inattendue est survenue lors de la création de l'administrateur."],
            $serviceResponseMessage,
            "L'API n'a pas retourné le message d'erreur attendu."
        );

        // Test que le code de statut HTTP est 500.
        $this->assertEquals(EnumHttpCode::SERVER_ERROR, $httpStatusCode, "L'API n'a pas retourné le code de status HTTP 500.");
    }
}