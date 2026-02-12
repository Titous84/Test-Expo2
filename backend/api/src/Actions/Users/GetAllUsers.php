<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant de gérer la page par défaut.
 */
class GetAllUsers
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

	/**
	 * Fonction invoquée lors de l'appelle de la classe DefaultAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
        $resultGetAllUsers = $this->userService->get_all_users();
		$response->getBody()->write($resultGetAllUsers->to_json());
		return $response->withStatus($resultGetAllUsers->get_http_code());
	}
}