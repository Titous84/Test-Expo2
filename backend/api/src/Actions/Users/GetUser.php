<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant d'obtenir un user par son activation_token.
 * @author Jean-Philippe Bourassa
 * @package App\Actions\Users
 */
class GetUser
{
	/**
	 * userService
	 *
	 * @var UserService Permet d'avoir accès au UserService
	 */
    private $userService;

	/**
	 * Constructeur. GetUser.
	 * @param UserService $userService Service permettant d'obtenir les utilisateurs.
	 */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetUser
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
        $id = $request->getAttribute('token');
        $resultGetUser = $this->userService->get_user_by_activation_token($token);
		$response->getBody()->write($resultGetAllUsers->to_json());
		return $response->withStatus($resultGetAllUsers->get_http_code());
	}
}