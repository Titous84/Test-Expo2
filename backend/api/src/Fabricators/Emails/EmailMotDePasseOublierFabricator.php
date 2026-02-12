<?php
namespace App\Fabricators\Emails;

use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;
use App\Models\Result;
use Symfony\Component\Dotenv\Dotenv;


/**
 * On charge Dotenv.
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../../../.env.prod');

/** 
 * Classe permettant d'envoyer un courriel suite à l'oublie du mot de passe
 * @author MaximeDemersBoucher
 * Fortement inspiré de EmailAdminFabricator
 * @package App\Fabricators\Emails
*/
class EmailMotDePasseOublierFabricator{
    private $emailService;
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
	 */
    public function send_mail(string $email,string $code):Result{
        $html = $this->twigService->twig->render('Emails/MotDePasseOublier.html.twig', [
			"url" => $_ENV["base_url"]."/modifier-mot-de-passe-oublie",
			"codeVerification" => $code
		]);
		$emailJSON = [
			"receiver"=>$email,
			"receiver_name"=>"",
			"receiver_last_name"=>"",
			"subject"=>"Réinitialisation de votre mot de passe d'Expo-Sat",
			"text_content"=>null,
			"html_content" => $html
		];
		$mail = new Email($emailJSON);
		return $this->emailService->send_mail($mail);
    }
}