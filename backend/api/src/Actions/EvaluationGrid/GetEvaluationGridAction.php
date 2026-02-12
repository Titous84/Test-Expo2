<?php

namespace App\Actions\EvaluationGrid;

use App\Services\EvaluationGridService;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;

/**
 * Classe permettant de récupérer les grilles d'évaluation.
 * @author Raphaël Boisvert
 *
 */
class GetEvaluationGridAction
{
    private $evaluationGridService;
    private $tokenService;

    public function __construct(EvaluationGridService $evaluationGridService, TokenService $tokenService)
    {
        $this->evaluationGridService = $evaluationGridService;
        $this->tokenService = $tokenService;
    }

    /**
     * Fonction invoquée lors de l'appelle de la classe DefaultAction.
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de réponse PSR-7.
     * @param array $args Arguments passés dans la requête.
     * @return ResponseInterface Réponse retournée par la route.
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $result = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin"]);

        if ($result !== null) {
            $response->getBody()->write($result->to_json());
            return $response->withStatus($result->get_http_code());
        }

        $evaluationGrid = $this->evaluationGridService->getEvaluationGrid();

        $response->getBody()->write($evaluationGrid->to_json());
        return $response->withStatus($evaluationGrid->get_http_code());
    }
}