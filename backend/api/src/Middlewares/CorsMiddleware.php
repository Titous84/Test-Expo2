<?php

namespace App\Middlewares;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

/**
 * Classe permettant les requêtes REST via Cors.
 */
class CorsMiddleware
{
	/**
	 * Fonction invoqué quand la classe CorsMiddleware est créer.
	 * @param Request $request Requête de type PSR-7.
	 * @param RequestHandler $handler Permet de gérer une requête et de produire une réponse.
	 * @return Response Réponse à retourner à l'utilisateur.
	 */
	public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
        $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

        // Optional: Allow Ajax CORS requests with Authorization header
        return $response->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}