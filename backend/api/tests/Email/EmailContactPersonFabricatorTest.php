<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use App\Services\EmailService;
use App\Services\TwigService;
use Test\TestsUtils\TeamInitialize;
use App\Fabricators\Emails\EmailContactPersonFabricator;
use Symfony\Component\Dotenv\Dotenv;
/**
 * EmailContactPersonFabricatorTest
 * @author Tristan Lafontaine
 */
class EmailContactPersonFabricatorTest extends TestCase{
    
    /**
	 * @author Christopher Boisvert
	 * @before
	 */
	public function set_up_environment()
	{
		//Configuration de l'environnement
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../.env.prod');
	}
    /**
     * test_send_email_contact_person
     * Fonction qui test l'envoi d'un courriel à un/une enseignant/e ressources.
     * @return void
     */
    public function test_send_email_contact_person(){
		echo date("Y-m-d h:m:s") . " Création servicle de PHP Mailer\n";
		$phpMailer = new PHPMailer(true);

		echo date("Y-m-d h:m:s") . " Création du service de courriel\n";
		$emailService = new EmailService($phpMailer);

		echo date("Y-m-d h:m:s") . " Création du service Twig\n";
		$twig = new TwigService();

		echo date("Y-m-d h:m:s") . " Création EmailContactPersonFabricator\n";
		$sendEmail = new EmailContactPersonFabricator($emailService, $twig);

		echo date("Y-m-d h:m:s") . " Initialisation d'une équipe\n";
		$teamInitaalize = new TeamInitialize();
		$team = $teamInitaalize->team();
		$response = $sendEmail->send_mail_contact_person($team->contactPerson[0]["fullName"], $team->contactPerson[0]["email"] , $team->members, $team->title, $team->description, $team->category, $team->year);
		$this->assertEquals($response->get_http_code(), 200);
	}
}