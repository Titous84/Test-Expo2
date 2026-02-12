<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant de valider le courriel d'un usager.
 * @namespace App\Actions\Users
 */
class ValidateEmail
{
    private $userService;
	/**
	 * Fonction invoquée lors de l'appelle de la classe ValidateEmail
	 * @author Mathieu Sévégny
	 * @param UserService $userService Service de gestion des usagers.
	 */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

	/**
	 * Fonction invoquée lors de l'appelle de la classe DefaultAction
     * @author Mathieu Sévégny
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
        $token = $request->getAttribute('token');
        $result = $this->userService->activate_email_by_token($token);
		$response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}