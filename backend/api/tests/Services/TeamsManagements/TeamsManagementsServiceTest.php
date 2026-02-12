<?php

namespace Tests\TeamsList\TeamsListServiceTest;

use App\Handlers\LogHandler;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Test\TestsUtils\PDOInitialize;
use App\Services\TeamsListService;
use App\Repositories\TeamsListRepository;
use App\Repositories\SignUpTeamRepository;
use App\Validators\ValidatorsTeamsInfos;
use App\Validators\ValidatorTeamsMembers;
use App\Services\SignUpTeamService;
use PHPMailer\PHPMailer\PHPMailer;
use App\Services\EmailService;
use App\Validators\ValidatorTeam;
use App\Services\TwigService;
use App\Utils\GeneratorUUID;
use Test\TestsUtils\TeamInitialize;
use App\Enums\EnumHttpCode;
use Test\TestsUtils\TestingLogger;

/**
 * Classe permettant de tester la paga de gestion des équipe.
 * @author Tristan Lafontaine
 * @package Tests\TeamsList\TeamsListServiceTest
 */
final class TeamsListServiceTest extends TestCase
{

    private static $pdo;

    private $mock = [
        "team" => [
            "team_id" => 310,
            "team_number" => 1,
            "title" => "Space",
            "description" => "Space",
            "year" => "2e année et +",
            "category" => "Sciences physiques",
            "survey" => "TemplateSAT",
            "teams_activated" => 0,
            "members" => "Olivier Lafontaine; Tristan Lafontaine",
            "contact_person_name" => "Olivier Grenier",
            "contact_person_email" => "olivier.grenier@cegepvicto.ca",
        ]
    ];

    private $mockMember = [
        "team" => [
            "id" => 591,
            "team_id" => 310,
            "team_number" => 1,
            "title" => "Space",
            "description" => "Space",
            "year" => "2e année et +",
            "category" => "Sciences physiques",
            "survey" => "TemplateSAT",
            "teams_activated" => 0,
            "email" => "tristanlaf003@live.ca",
            "first_name" => "Tristan",
            "last_name" => "Lafontaine",
            "activated" => 0,
            "blacklisted" => 1,
            "contact_person_name" => "Tristan",
            "contact_person_email" => "tristan.lafontaine@cegepvicto.ca",
            "contact_person_id" => 11,
            "picture_consent" => 1,
        ]
    ];

    /**
     * setUpBeforeClass
     * Permet de créer une instance de PDO
     */
    public static function setUpBeforeClass(): void
    {
        //Configuration de l'environnement
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../../.env.prod');
        self::$pdo = new PDOInitialize();
    }

    /**
     * tearDownAfterClass
     * Permet de supprimer l'instance du PDO
     */
    public static function tearDownAfterClass(): void
    {
        self::$pdo = null;
    }

    /**
     * Fonction qui teste l'obtention des données des membres et des équipes
     */
    public function test_get_all_teams_and_members()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        $teamsListService = $this->TeamsListService();

        $this->createTeam();

        echo date("Y-m-d h:m:s") . " Obtenir tous les équipes\n";
        $reponse = $teamsListService->get_all_teams_and_members();

        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());
    }

    /**
     * Fonction qui teste l'obtention des données des membres concaténées et des équipes
     */
    public function test_get_all_teams_and_members_concat()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        $teamsListService = $this->TeamsListService();

        echo date("Y-m-d h:m:s") . " Obtenir tous les éuiqpes\n";
        $reponse = $teamsListService->get_all_teams_and_members_concat();

        $this->assertEquals("string", gettype($reponse->get_content()[0]['members']));
    }

    /**
     * Test le get d'une équipe grâce à son id.
     */
    public function test_get_team_by_id()
    {
        echo date("Y-m-d h:m:s") . " Test du select d'une equipe grace a son id.\n";
        $teamsListService = $this->TeamsListService();

        $this->createTeam();

        $id = $this->get_team_id_last_element_insert();

        $response = $teamsListService->get_team_and_members($id);

        if ($response->get_http_code() == EnumHttpCode::SUCCESS) {
            $teamInitialize = new TeamInitialize();
            $team = $teamInitialize->Team();
            $inserted_team = (object) $response->get_content();
            $inserted_team = $inserted_team->team;

            $this->assertEquals($team->title, $inserted_team->title);
            $this->assertEquals($team->description, $inserted_team->description);
        } else {
            $this->fail("L'équipe n'as pas pus être retrouvé.");
        }

        $this->delete_team();
    }

    /**
     * Test si le get d'equipe par id retourne bien not found lorsqu'aucune equipe n'est trouver.
     */
    public function test_get_team_by_id_non_existing_team()
    {
        echo date("Y-m-d h:m:s") . " Test du select d'une equipe qui n'existe pas.\n";
        $teamsListService = $this->TeamsListService();

        $response = $teamsListService->get_team_and_members(0);

        $this->assertEquals(EnumHttpCode::NOT_FOUND, $response->get_http_code());
    }

    /**
     * Test si le get d'equipe par id retourne bien not found lorsque l'id est en bas de zero.
     */
    public function test_get_team_by_id_under_zero()
    {
        echo date("Y-m-d h:m:s") . " Test du select d'une equipe avec un id en dessous de zero.\n";
        $teamsListService = $this->TeamsListService();

        $response = $teamsListService->get_team_and_members(-1);

        $this->assertEquals(EnumHttpCode::NOT_FOUND, $response->get_http_code());
    }

    /**
     * Test si le get d'equipe par id retourne bien not found lorsque l'id est très haut.
     */
    public function test_get_team_by_id_big_number() {
        echo date("Y-m-d h:m:s") . " Test du select d'une equipe avec un id tres haut.\n";
        $teamsListService = $this->TeamsListService();
        
        $response = $teamsListService->get_team_and_members(PHP_INT_MAX);
        $this->assertEquals(EnumHttpCode::NOT_FOUND, $response->get_http_code());
    }

    /**
     * Fonction qui test la mise à jour de données pour les informations d'une équipe
     */
    public function test_update_teams_infos()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        $teamsListService = $this->TeamsListService();

        $this->mock['team']['team_id'] = $this->get_team_id_last_element_insert();
        $this->mock['team']['teams_activated'] = 1;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_teams_infos($this->mock);
        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());

        $this->mock['team']['team_id'] = $this->get_team_id_last_element_insert();
        $this->mock['team']['teams_activated'] = 0;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_teams_infos($this->mock);
        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());
    }

    /**
     * Fonction qui test la mise à jour des informations d'un membre
     */
    public function test_update_teams_members()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        $teamsListService = $this->TeamsListService();

        $this->mockMember['team']['id'] = $this->get_team_id_last_element_insert_members();
        $this->mockMember['team']['users_activated'] = 1;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_team_member($this->mockMember);

        TestingLogger::log($reponse->to_json());

        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());

        $this->mockMember['team']['id'] = $this->get_team_id_last_element_insert_members();
        $this->mockMember['team']['users_activated'] = 0;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_team_member($this->mockMember);
        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());
    }

    /**
     * Fonction qui test la mise à jour des informations les numéros de stand
     */
    public function test_update_teams_numbers()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        $teamsListService = $this->TeamsListService();

        $this->mockMember['team']['team_id'] = $this->get_team_id_last_element_insert();
        $this->mockMember['team']['team_number'] = 50;
        $array = [$this->mockMember['team']];
        $arrayTeam['team'] = $array;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_teams_numbers($arrayTeam);
        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());

        $this->mockMember['team']['team_id'] = $this->get_team_id_last_element_insert();
        $this->mockMember['team']['team_number'] = 1;
        $array = [$this->mockMember['team']];
        $arrayTeam['team'] = $array;

        echo date("Y-m-d h:m:s") . " Mettre à jour une équipe\n";
        $reponse = $teamsListService->update_teams_numbers($arrayTeam);
        $this->assertEquals(EnumHttpCode::SUCCESS, $reponse->get_http_code());

        $this->delete_team();
    }

    /**
     * Fomction qui permet de créer une nouvelle équipe.
     * @return int
     */
    public function createTeam()
    {
        echo date("Y-m-d h:m:s") . " Création servicle de PHP Mailer\n";
        $phpMailer = new PHPMailer(true);

        echo date("Y-m-d h:m:s") . " Création du service de courriel\n";
        $emailService = new EmailService($phpMailer);

        echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
        $signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), new LogHandler());

        echo date("Y-m-d h:m:s") . " Création du validateur d'équipe\n";
        $validatorTeam = new ValidatorTeam($signUpTeamRepository);

        echo date("Y-m-d h:m:s") . " Appel la classe GeneratorUuid\n";
        $generatorUUID = new GeneratorUuid();

        echo date("Y-m-d h:m:s") . " Création du service Twig\n";
        $twig = new TwigService();

        echo date("Y-m-d h:m:s") . " Création du service signUpTeamService\n";
        $signUpTeamService = new SignUpTeamService($signUpTeamRepository, $validatorTeam, $emailService, $twig);

        echo date("Y-m-d h:m:s") . " Initilisation des Team\n";
        $teamInitialize = new TeamInitialize();
        $teamObject = $teamInitialize->Team();
        $team = ["team" => (array) $teamObject];
        echo date("Y-m-d h:m:s") . " Appel la fonction pour ajouter une équipe\n";
        $response = $signUpTeamService->add_signup_team($team);
        return $response->get_http_code();
    }

    /**
     * Fonction qui permet de créer le service TeamsListService
     * @return TeamsListService
     */
    public function TeamsListService()
    {
        $logHandler = new LogHandler();

        echo date("Y-m-d h:m:s") . " Changement de la variable ENV en mode développement\n";
        $_ENV["production"] = "false";

        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListRepository\n";
        $teamsListRepository = new TeamsListRepository(self::$pdo->PDO(), $logHandler);

        echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository\n";
        $signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), $logHandler);

        echo date("Y-m-d h:m:s") . " Création du répertoire ValidatorsTeamsInfos\n";
        $validatorsTeamsInfos = new ValidatorsTeamsInfos();

        echo date("Y-m-d h:m:s") . " Création du répertoire ValidatorTeamsMembers\n";
        $validatorTeamsMembers = new ValidatorTeamsMembers();


        echo date("Y-m-d h:m:s") . " Création du répertoire TeamsListService\n";
        return new TeamsListService($teamsListRepository, $validatorsTeamsInfos, $validatorTeamsMembers, $signUpTeamRepository, new LogHandler());
    }

    /**
     * Fonction qui permet de récupérer l'id de la dernière équipe inscrite
     * @return int Retourne l'id de la dernière équipe inscrite
     */
    public function get_team_id_last_element_insert()
    {
        $query = self::$pdo->PDO()->query("SELECT id FROM teams ORDER BY id DESC LIMIT 1");
        $response = $query->fetch();
        return $response["id"];
    }

    /**
     * Fonction qui permet de récupérer l'id du dernier membre inscrit
     * @return int Retourne l'id du dernier membre inscrit
     */
    public function get_team_id_last_element_insert_members()
    {
        echo date("Y-m-d h:m:s") . " Obtenir tous les éuiqpes\n";
        $teamsListService = $this->TeamsListService();
        $reponse = $teamsListService->get_all_teams_and_members();
        $size = sizeof($reponse->get_content());
        return $reponse->get_content()[$size - 1]['id'];
    }

    /**
     * test_delete_team
     * Fonction qui permet de tester la suppresion d'une équipe ou de plusieurs équipes
     * @return void
     */
    public function delete_team()
    {
        echo date("Y-m-d h:m:s") . " Création du répertoire SignUpTeamRepository \n";
        $signUpTeamRepository = new SignUpTeamRepository(self::$pdo->PDO(), new LogHandler());

        echo date("Y-m-d h:m:s") . " Obtenir tous les équipes de la bd. \n";
        $teams = $signUpTeamRepository->get_all_team_by_title_and_description('Space', 'Space');

        $sizeofArray = sizeof($teams);
        for ($a = 0; $a < $sizeofArray; $a++) {
            $response = $signUpTeamRepository->delete_team($teams[$a]["id"]);
            $this->assertEquals(1, $response, "Erreur : test_delete_team");
        }
    }
}