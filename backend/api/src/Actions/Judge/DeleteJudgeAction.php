<?php

namespace App\Actions\Judge;

use App\Services\UserService;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;

/**
 * Classe permettant de supprimer un juge.
 * @author Étienne Nadeau
 * @package App\Actions\Judge
 */
class DeleteJudgeAction
{
    private $judgeService;
    private $tokenService;

    /**
     * Constructeur de la classe DeleteJudgesAction
     * @author Étienne Nadeau
     * @param  userService $judgeService
     * @param  TokenService $tokenService
     * @return void
     */
    public function __construct(UserService $judgeService, TokenService $tokenService)
    {
        $this->judgeService = $judgeService;
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
        $user_id = $request->getAttribute("id");
        $result = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin"]);

        if ($result !== null) {
            $response->getBody()->write($result->to_json());
            return $response->withStatus($result->get_http_code());
        }
        

        $judge = $this->judgeService->delete_judge($user_id);

        $response->getBody()->write($judge->to_json());
        return $response->withStatus($judge->get_http_code());
    }
}