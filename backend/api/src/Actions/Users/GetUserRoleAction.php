<?php

namespace App\Actions\Users;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;

/**
 * Classe permettant d'obtenir un rôle d'un utilisateur.
 * @package App\Actions\Users
 */
class GetUserRoleAction
{
	private $tokenService;

	public function __construct(TokenService $tokenService)
	{
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetUserRoleAction.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$token = TokenUtils::get_token_from_request($request);
		//Find token in request
		if ($token === null)
		{
			$badResponse = new Result(EnumHttpCode::FORBIDDEN,["Il n'y a pas de jeton d'envoyé!"],null);
			$response->getBody()->write($badResponse->to_json());
			return $response->withStatus($badResponse->get_http_code());
		}
		//Find user role id in token
		$roleId = TokenUtils::get_role_id_from_token($this->tokenService,$token);

		if (!isset($roleId) || !is_int($roleId)){
			$badResponse = new Result(EnumHttpCode::FORBIDDEN,["Le jeton est invalide!"],null);
			$response->getBody()->write($badResponse->to_json());
			return $response->withStatus($badResponse->get_http_code());
		}
		//Find role name in db
		$roleName = $this->tokenService->get_role_name($roleId);
		if ($roleName === null){
			$badResponse = new Result(EnumHttpCode::FORBIDDEN,["Le role n'a pas été trouvé!"],null);
			$response->getBody()->write($badResponse->to_json());
			return $response->withStatus($badResponse->get_http_code());
		}
		$content = [
			"id" => $roleId,
			"name" => $roleName
		];

		$goodResponse = new Result(EnumHttpCode::SUCCESS,["Le jeton est valide!"],$content);
		$response->getBody()->write($goodResponse->to_json());
		return $response->withStatus($goodResponse->get_http_code());
	}
}