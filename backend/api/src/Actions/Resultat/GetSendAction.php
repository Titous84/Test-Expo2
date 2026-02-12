<?php

namespace App\Actions\Resultat;

use App\Services\ResultatSendService;
use Psr\Http\Message\ResponseInterface;
use App\Enums\EnumHttpCode;
use App\Fabricators\Emails\EmailSendResultFabricator;
use App\Services\EmailService;
use App\Services\TwigService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;

/**
 * Classe permettant denvoyer le mail.
 * @author Souleymane Soumaré <s.soumare@hotmail.fr>
 */
/** 
 * Classe permettant d'obtenir un tableau de resultat.
 */
class GetSendAction
{

	private $twigService;
	private $emailService;
	private $tokenService;

	public function __construct(TwigService $twigService, EmailService $emailService,TokenService $tokenService)
	{
		$this->twigService = $twigService;
		$this->emailService = $emailService;
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetResultatAction.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}
		$fabricatorSendResultFabricator = new EmailSendResultFabricator($this->emailService,$this->twigService);
        $resultSendResult = $fabricatorSendResultFabricator->send_mail($request->getParsedBody());

		// Build the HTTP response
        $response->getBody()->write($resultSendResult->to_json());
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($resultSendResult->get_http_code());

	}
}