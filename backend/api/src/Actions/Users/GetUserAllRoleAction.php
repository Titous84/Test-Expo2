<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Services\TokenService;

/**
 * Classe permettant d'obtenir tous les rôles
 * @author Tristan Lafontiane
 * @package App\Actions\Users
 */
class GetUserAllRoleAction
{
	private $UserService;

	/**
	 * @var TokenService Dépôt liée au service TokenService
	 */
	private $tokenService;

	public function __construct(UserService $UserService, TokenService $tokenService)
	{
		$this->UserService = $UserService;
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetActiveUsers.
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

		$resultGetActivatedUsers = $this->UserService->get_all_roles();
		$response->getBody()->write($resultGetActivatedUsers->to_json());
		return $response->withStatus($resultGetActivatedUsers->get_http_code());
	}
}