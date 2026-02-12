<?php

namespace App\Actions\Token;

use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant d'obtenir un token.
 */
class GetTokenAction
{
	private $tokenService;

	public function __construct(TokenService $tokenService)
	{
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetTokenAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$resultGetToken = $this->tokenService->get_token($request->getParsedBody());
		$response->getBody()->write($resultGetToken->to_json());
		return $response->withStatus($resultGetToken->get_http_code());
	}
}