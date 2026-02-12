<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Models\Result;
use App\Enums\EnumHttpCode;
use App\Services\TokenService;

/**
 * Classe permettant d'ajouter un user.
 * @author Maxime Demers Boucher
 * @package App\Actions\Users
 */
class AddUserAction
{
    /**
     * Service du user
     */
    private $UserService;
    /**
     * Service pour le token
     */
	private $tokenService;

    /**
     * Constructeur de la classe
     */
    public function __construct(UserService $UserService, TokenService $tokenService)
	{
		$this->UserService = $UserService;
		$this->tokenService = $tokenService;
	}

    /**
     * inspiré de get ActiveUserAction
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
		$body = $request->getParsedBody();
		$resultAddUser = $this->UserService->add_user($body);
		$response->getBody()->write($resultAddUser->to_json());
		return $response->withStatus($resultAddUser->get_http_code());
	}
}