<?php
namespace App\Fabricators\Emails;

use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;
use Exception;
use App\Models\Result;
use App\Enums\EnumHttpCode;

/** 
 * Classe permettant d'envoyer un courriel de validation 
 * @author Mathieu Sévégny
 * @package App\Fabricators\Emails
*/
class EmailValidationFabricator
{
    /**
     * @var EmailService $emailService Service permettant d'envoyer des courriels.
     */
    private $emailService;

    /**
     * @var TwigService $twigService Service permettant d'obtenir le code html d'un courriel.
     */
    private $twigService;

	/**
	 * Fonction invoquée lors de l'appelle de la classe EmailValidationFabricator
	 * @author Mathieu Sévégny
	 * @param EmailService $emailService Service de gestion des courriels.
	 * @param TwigService $twigService Service de gestion des templates Twig.
	 */
    public function __construct(EmailService $emailService,TwigService $twigService)
	{
        $this->emailService = $emailService;
        $this->twigService =  $twigService;
    }

	/**
	 * Fonction pour envoyer un courriel pour demander la validation d'un courriel.
	 * @author Mathieu Sévégny
	 * @param string $email_address Adresse courriel de l'usager.
	 * @param string $first_name Prénom de l'usager.
	 * @param string $last_name Nom de l'usager.
	 * @param string $token Token de validation.
	 */
    public function send_mail(string $email, string $firstName, string $lastName, string $token)
	{
		try
		{
			$html = $this->twigService->twig->render('Emails/AccountActivation.html.twig', [
				"name" => $firstName." ".$lastName,
				"buttonUrl" => $_ENV["base_url"]."/validation-courriel/".$token,
				"buttonText" => "Valider l'adresse courriel",
				"baseURL"=>$_ENV["base_url"]
			]);
			$emailJSON = [
				"receiver"=>$email,
				"receiver_name"=>$firstName,
				"receiver_last_name"=>$lastName,
				"subject"=>"ExpoSAT - Validation de l'adresse courriel",
				"text_content"=>null,
				"html_content" => $html
			];
			$mail = new Email($emailJSON);
			return $this->emailService->send_mail($mail);
		}
		catch(Exception $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("La maquette Twig n'a pas pu être chargé."));
		}
    }
}