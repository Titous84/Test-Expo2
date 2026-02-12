<?php
namespace App\Fabricators\Emails;

use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;
use Exception;
use App\Models\Result;
use App\Enums\EnumHttpCode;

/** 
 * Classe permettant d'envoyer un courriel suite à l'ajout d'un admin
 * @author MaximeDemersBoucher
 * Fortement inspiré de EmailValidationFabricator.php
 * @package App\Fabricators\Emails
*/
class EmailAdminFabricator{
	/**
	 * @var EmailService $emailService Service permettant d'envoyer des courriels.
	 */
	private $emailService;

	/**
	 * @var TwigService $twigService Service permettant d'obtenir le code html d'un courriel.
	 */
		private $twigService;

	/**
	 * Fonction invoquée lors de l'appelle de la classe EmailJudgeFabricator
	 * @author Maxime Demers Boucher
	 * @param EmailService $emailService Service de gestion des courriels.
	 * @param TwigService $twigService Service de gestion des templates Twig.
	 */
    public function __construct(EmailService $emailService,TwigService $twigService){
        $this->emailService = $emailService;
        $this->twigService =  $twigService;
    }
	/**
	 * Fonction pour envoyer un courriel pour demander l'inscription d'un juge.
	 * @author Maxime Demers Boucher
	 * @param string $email_address Adresse courriel de l'usager.
	 * @param string $first_name Prénom de l'usager.
	 * @param string $last_name Nom de l'usager.
	 * @param string $pwd Mot de passe du user
	 * @param string $token Token de validation.
	 */
    public function send_mail(string $email, string $firstName, string $lastName, string $username){
        $html = $this->twigService->twig->render('Emails/AjoutAdmin.html.twig', [
			"name" => $firstName." ".$lastName,
			"pwd" => $username
		]);
		$emailJSON = [
			"receiver"=>$email,
			"receiver_name"=>$firstName,
			"receiver_last_name"=>$lastName,
			"receiver_username"=>$username,
			"subject"=>"ExpoSAT - Vous êtes maintenant un/e administrateur/rice!",
			"text_content"=>null,
			"html_content" => $html
		];
		$mail = new Email($emailJSON);
		return $this->emailService->send_mail($mail);
    }
}