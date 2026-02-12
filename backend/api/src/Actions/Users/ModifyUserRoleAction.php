<?php

namespace App\Actions\Users;

use App\Enums\EnumHttpCode;
use App\Services\UserService;
use App\Utils\TokenUtils;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Models\Result;

/**
 * Classe permettant de modifier le rôle d'un utilisateur.
 * @author Alex Des Ruisseaux
 * @package App\Actions\Users
 */
class ModifyUserRoleAction
{
	private $userService;
	private $tokenService;

	public function __construct(UserService $userService, TokenService $tokenService)
	{
		$this->userService = $userService;
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe ChangeUserRole.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$token = TokenUtils::get_token_from_request($request);

		if(is_null($token))
		{
			$badResponse = new Result(EnumHttpCode::FORBIDDEN, ["Il n'y a pas de jeton d'envoyé!"], null);
			$response->getBody()->write($badResponse->to_json());
			return $response->withStatus($badResponse->get_http_code());
		}

		$role_id = TokenUtils::get_role_id_from_token($this->tokenService, $token);

		if($role_id != 0){
			$badResponse = new Result(EnumHttpCode::FORBIDDEN, ["Vous n'avez pas accès à cette fonctionnalité."], null);
			$response->getBody()->write($badResponse->to_json());
			return $response->withStatus($badResponse->get_http_code());
		} 

		$resultChangeUserRole = $this->userService->modify_user_role($request->getParsedBody());
		$response->getBody()->write($resultChangeUserRole->to_json());
		return $response->withStatus($resultChangeUserRole->get_http_code());
	}
}