<?php

namespace App\Actions\JudgeStand;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\JudgeStandService;
use App\Services\TokenService;
use App\Utils\TokenUtils;

/**
 * Classe permettant d'enregistrer un evaluation a un stand.
 * @author Xavier Houle
 * @package App\Actions\JudgeStandEval
 */
class PatchEvaluationAction
{	
	/**
	 * JudgeStandService
	 *
	 * @var JudgeStandService Permet d'avoir accès au UserService
	 */
	private $judgeStandService;
	private $tokenService;

	/**
	 * Constructeur. PostJudgeUserAction.
	 * @param JudgeStandService $judgeStandService Service permettant d'obtenir les stands.
	 */
    public function __construct(JudgeStandService $judgeStandService, TokenService $tokenService)
    {
		$this->judgeStandService = $judgeStandService;
		$this->tokenService = $tokenService;
    }
	/**
	 * Fonction invoquée lors de l'appelle de la classe PostEvaluationAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService, ["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}

		$resultJudge = $this->judgeStandService->update_evaluation($request->getParsedBody());
        $response->getBody()->write($resultJudge->to_json());
		return $response->withStatus($resultJudge->get_http_code());
	}
}