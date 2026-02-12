<?php
namespace App\Fabricators\Emails;

use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;
use Exception;
use App\Models\Result;
use App\Enums\EnumHttpCode;

/** 
 * Classe permettant d'envoyer un courriel contenant les résultats
 * @namespace App\Fabricators\Emails
*/
class EmailSendResultFabricator
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
	 * @author Souleymane Soumaré
	 * @param EmailService $emailService Service de gestion des courriels.
	 * @param TwigService $twigService Service de gestion des templates Twig.
	 */
    public function __construct(EmailService $emailService,TwigService $twigService)
	{
        $this->emailService = $emailService;
        $this->twigService =  $twigService;
    }

	/**
	 * Fonction pour envoyer un courriel avec la note.
	 * @author Souleymane Soumaré
	 * @param mixed $monDATA .
	 */
    public function send_mail( $monDATA )
	{
		try
		{
			//TODO: Faire les vérifications avec un validator ici

			$html = $this->twigService->twig->render('Emails/NoteMail.html.twig', [
				"name_ressource_person" => $monDATA["name_ressource_person"],
				"team_name"=> $monDATA["team_name"],
				"note"=> $monDATA["note"],
				"baseURL"=>$_ENV["base_url"]
			]);
			$emailJSON = [
				"receiver"=> $monDATA["email_ressource_person"],
				"receiver_name"=> $monDATA["name_ressource_person"],
				"receiver_last_name" => "",
				"subject"=> "ExpoSAT - Envoie de notes",
				//TODO: Obligatoire
				"text_content"=> null,
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