<?php

namespace App\Actions;

use App\Enums\EnumHttpCode;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant de gérer la page par défaut.
 */
class DefaultAction
{
	/**
	 * Fonction invoquée lors de l'appelle de la classe DefaultAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$body = $response->getBody();
		$body->write('Hello');
		//Mécanique de vérification de l'utilisateur
		return $response->withStatus(EnumHttpCode::FORBIDDEN);
	}
}