<?php
namespace App\Fabricators\Emails;

use App\Enums\EnumHttpCode;
use App\Repositories\Repository;
use App\Services\EmailService;
use App\Services\TwigService;
use App\Models\Email;
use Exception;
use App\Models\Result;

/** 
 * Classe permettant d'envoyer un courriel de validation 
 * @namespace App\Fabricators\Emails
*/
class EmailContactPersonFabricator extends Repository
{ 
	/**
	 * @var EmailService $emailService Service permettant d'avoir des courriels.
	 */
    private $emailService;

	/**
	 * @var TwigService $twigService Service permettant d'accéder aux maquettes Twig.
	 */
    private $twigService;
	/**
	 * Fonction invoquée lors de l'appelle de la classe EmailValidationFabricator
	 * @author Tristan Lafontaine
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
	 * @author Tristan Lafontaine
	 * @param string $email_address Adresse courriel de l'usager.
	 * @param string $first_name Prénom de l'usager.
	 * @param string $last_name Nom de l'usager.
	 * @param string $token Token de validation.
	 */
    public function send_mail_contact_person( string $fullName, string $emailContactPerson, array $members, string $title, string $description, string $category, string $year)
	{
		try
		{
			$html = $this->twigService->twig->render('Emails/TeamInfoSignUp.html.twig', [
				"nameContactPerson" => $fullName,
				"members" => $members,
				"title" => $title,
				"description"=>$description,
				"category"=>$category,
				"year"=>$year,
				"baseURL"=>$_ENV["base_url"]
			]);
			$emailJSON = [
				"receiver"=>$emailContactPerson,
				"receiver_name"=>$emailContactPerson,
				"receiver_last_name"=>null,
				"subject"=>"ExpoSAT - Inscription d'une nouvelle équipe",
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