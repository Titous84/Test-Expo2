<?php

namespace Services\EmailService;

use App\Enums\EnumHttpCode;
use App\Models\Email;
use App\Services\EmailService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Test\TestsUtils\TestingLogger;
use function PHPUnit\Framework\assertEquals;

/**
 * Classe permettant de tester le service de courriel.
 * @author Christopher Boisvert
 * @package Services\EmailService
 */
class EmailServiceTest extends TestCase {

	/**
	 * @author Christopher Boisvert
	 * @before
	 */
	public function set_up_environment()
	{
		//Configuration de l'environnement
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../../.env.prod');
	}

	/**
	 * Méthode de test vérifiant si les courriels peuvent s'envoyer.
	 * @author Christopher Boisvert
	 * @test
	 */
	public function send_mail_dev_test()
	{
		TestingLogger::log("Changement de la variable ENV en mode développement");
		$_ENV["production"] = "false";

		TestingLogger::log("Création du courriel");

		$email = new Email(array(
			"receiver" => $_ENV["test_email_receiver"],
			"receiver_name" => "Nom",
			"receiver_last_name" => "Prénom",
			"subject" => "send_mail_dev_test",
			"text_content" => "Ceci est un courriel de test."
		));

		TestingLogger::log("Création de l'objet PhpMailer");
		$phpMailer = new PHPMailer(true);

		TestingLogger::log("Création de l'objet CourrielService");
		$emailService = new EmailService($phpMailer);

		TestingLogger::log("Envoi du courriel à ". $email->receiver);
		$resultEmailSent = $emailService->send_mail($email);

		TestingLogger::log("Vérification de la réussite de l'envoi du courriel");
		assertEquals( EnumHttpCode::SUCCESS, $resultEmailSent->get_http_code());
	}
}
