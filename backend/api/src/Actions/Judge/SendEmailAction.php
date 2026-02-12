<?php

namespace App\Actions\Judge;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\UserService;

/**
 * Classe permettant d'envoyer les emails d'inscription aux juges.
 * @author Jean-Philippe Bourassa
 * @package App\Actions\Judge
 */
class SendEmailAction
{	
	/**
	 * userService
	 *
	 * @var UserService Permet d'avoir accès au UserService
	 */
	private $userService;

	/**
	 * Constructeur. SendEmailAction.
	 * @param UserService $userService Service permettant d'obtenir les utilisateurs.
	 */
    public function __construct(UserService $userService)
    {
		$this->userService = $userService;
    }
	/**
	 * Fonction invoquée lors de l'appelle de la classe SendEmailAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$result = $this->userService->send_email_judges();
        $response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}