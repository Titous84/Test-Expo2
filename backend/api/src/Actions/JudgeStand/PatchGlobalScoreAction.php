<?php

namespace App\Actions\JudgeStand;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\JudgeStandService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe pour mettre à jour la valeur du champs de l'exclusion de la note globale d'un juge (global_score_removed) pour une évaluation donnée.
 * 
 * @author Francis PAYAN
 */
class PatchGlobalScoreAction
{
    /**
     * @var JudgeStandService
     */
    private $judgeStandService;

    /**
     * Constructeur de l'action PatchGlobalScoreAction.
     * 
     * @author Francis PAYAN
     * @param JudgeStandService $judgeStandService
     */
    public function __construct(JudgeStandService $judgeStandService)
    {
        $this->judgeStandService = $judgeStandService;
    }

    /**
     * Méthode invoquée lors de l'appel de l'action. Elle met à jour le statut de suppression de la note globale.
     * 
     * @author Francis PAYAN
     * Code inspiré des autres fichiers Actions de mannière à respecter la structure du projet.
     *
     * @param ServerRequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP.
     * @param array $args Les arguments passés dans l'URL.
     * @return ResponseInterface La réponse HTTP modifiée.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $judge_id = $request->getAttribute('judge_id');

        // Appel au service pour mettre à jour le statut de suppression de la note globale.
        $result = $this->judgeStandService->updateGlobalScoreRemoved($judge_id, $request->getParsedBody());

        // Écriture du corps de la réponse et modification des en-têtes.
        $response->getBody()->write(json_encode($result->to_json()));
        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($result->get_http_code());
    }
}
