<?php

namespace App\Actions\JudgeStand;

use App\Services\JudgeStandService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;

/**
 * Classe qui permet d'obtenir les états d'exclusion des notes pour les évaluations.
 */
class GetScoreExclusionsAction
{
    private $judgeStandService;
    private $tokenService;

    /**
     * Constructeur de l'action GetScoreExclusionsAction.
     * 
     * @author Francis PAYAN
     * @param JudgeStandService $judgeStandService Service pour les juges.
     * @param TokenService $tokenService Service pour les tokens.
     */
    public function __construct(JudgeStandService $judgeStandService, TokenService $tokenService)
    {
        $this->judgeStandService = $judgeStandService;
        $this->tokenService = $tokenService;
    }

    /**
     * Méthode invoquée lors de l'appel de l'action.
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Actions de mannière à respecter la structure du projet.
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de réponse PSR-7.
     * @param array $args Arguments passés dans la requête.
     * @return ResponseInterface La réponse HTTP.
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        // Vérification des rôles permis pour l'utilisateur.
        $result = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin"]);

        // Si l'utilisateur n'a pas les droits nécessaires, renvoie une réponse avec l'erreur.
        if ($result != null){
            $response->getBody()->write($result->to_json());
            return $response->withStatus($result->get_http_code());
        }

        // Appel au service pour obtenir les états d'exclusion des notes globales.
        $result = $this->judgeStandService->getScoreExclusions();
        $response->getBody()->write($result->to_json());
        return $response->withStatus($result->get_http_code());
    }
}
