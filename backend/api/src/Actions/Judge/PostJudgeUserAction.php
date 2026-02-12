<?php

namespace App\Actions\Judge;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\UserService;

/**
 * Classe permettant d'enregistrer un juge.
 * @author Jean-Philippe Bourassa
 * @package App\Actions\Judge
 */
class PostJudgeUserAction
{	
	/**
	 * userService
	 *
	 * @var UserService Permet d'avoir accès au UserService
	 */
	private $userService;

	/**
	 * Constructeur. PostJudgeUserAction.
	 * @param UserService $userService Service permettant d'obtenir les utilisateurs.
	 */
    public function __construct(UserService $userService)
    {
		$this->userService = $userService;
    }
	/**
	 * Fonction invoquée lors de l'appelle de la classe PostJudgeUserAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$resultJudge = $this->userService->add_judge_user($request->getParsedBody());
        $response->getBody()->write($resultJudge->to_json());
		return $response->withStatus($resultJudge->get_http_code());
	}
}