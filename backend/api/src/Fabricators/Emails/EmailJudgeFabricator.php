<?php
namespace App\Fabricators\Emails;

use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;

/** 
 * Classe permettant d'envoyer un courriel d'inscription aux juges
 * @author Jean-Philippe Bourassa
 * @package App\Fabricators\Emails
*/
class EmailJudgeFabricator{
    private $emailService;
    private $twigService;
	/**
	 * Fonction invoquée lors de l'appelle de la classe EmailJudgeFabricator
	 * @author Jean-Philippe Bourassa
	 * @param EmailService $emailService Service de gestion des courriels.
	 * @param TwigService $twigService Service de gestion des templates Twig.
	 */
    public function __construct(EmailService $emailService,TwigService $twigService){
        $this->emailService = $emailService;
        $this->twigService =  $twigService;
    }
	/**
	 * Fonction pour envoyer un courriel pour demander l'inscription d'un juge.
	 * @author Jean-Philippe Bourassa
	 * @author Jean-Christophe Demers
	 * @param string $email_address Adresse courriel de l'usager.
	 * @param string $first_name Prénom de l'usager.
	 * @param string $last_name Nom de l'usager.
	 * @param string $uuid Token de validation.
	 */
    public function send_mail(string $email, string $firstName, string $lastName, string $uuid){
        $html = $this->twigService->twig->render('Emails/JudgeActivation.html.twig', [
			"name" => $firstName." ".$lastName,
			"buttonUrl" => $_ENV["base_url"]."/effectuer-evaluation/".$uuid,
			"buttonText" => "S'inscrire",
			"baseURL"=>$_ENV["base_url"]
		]);
		$emailJSON = [
			"receiver"=>$email,
			"receiver_name"=>$firstName,
			"receiver_last_name"=>$lastName,
			"subject"=>"ExpoSAT - Juge",
			"text_content"=>null,
			"html_content" => $html
		];
		$mail = new Email($emailJSON);
		return $this->emailService->send_mail($mail);
    }
}