<?php

namespace App\Actions\TeamsList;

use App\Services\TeamsListService;
use App\Services\TokenService;
use App\Utils\TokenUtils;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe qui permet la requête des informations d'une équipe.
 */
class GetTeamInfoById
{
    /**
     * @var TeamsListService
     */
    private $teamsListService;

    /**
     * @var TokenService
     */
    private $tokenService;

    /**
     * Constructeur de la classe GetTeamsInfoById
     * 
     * @param TeamsListService $teamsListService
     * @param TokenService $tokenService
     */
    public function __construct(TeamsListService $teamsListService, TokenService $tokenService)
    {
        $this->teamsListService = $teamsListService;
        $this->tokenService = $tokenService;
    }

    /**
     * Fonction invoquée lors de l'appelle de la classe
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de requête PSR-7.
     * @param mixed $args Arguments passés dans las requête.
     * @return ResponseInterface Réponse retournée par la route.
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $tokenResult = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin", "Correcteur"]);

        if ($tokenResult != null) {
            $response->getBody()->write($tokenResult->to_json());
            return $response->withStatus($tokenResult->get_http_code());
        }

        $team = $this->teamsListService->get_team_and_members($args["id"]);

        $response->getBody()->write($team->to_json());
        return $response->withStatus($team->get_http_code());
    }
}
