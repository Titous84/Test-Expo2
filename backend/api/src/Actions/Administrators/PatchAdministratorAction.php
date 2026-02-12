<?php

namespace App\Actions\Judge;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\userService;
use App\Utils\TokenUtils;
use App\Services\TokenService;

/**
 * Classe permettant de modifier un juge.
 * @author Thomas-Gabriel Paquin
 * @package App\Actions\Judge
 */
class PatchJudgeAction
{	

	/**
	 * @var userService Dépôt liée à la classe UserService
	 */
	private $userService;
	
	/**
	 * @var TokenService Dépôt liée au service TokenService
	 */
	private $tokenService;
	
	/**
     * Constructeur de la classe PatchJudgeAction
     * @author Thomas-Gabriel Paquin
     * @param  userService $userService
     * @param  TokenService $tokenService
     * @return void
     */
    public function __construct(userService $userService, TokenService $tokenService)
    {
		$this->userService = $userService;
		$this->tokenService = $tokenService;
    }
	
	/**
	 * Fonction invoquée lors de l'appelle de la classe DefaultAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}
        
		$judgeManagement = $this->userService->update_judge_infos($request->getParsedBody());

        $response->getBody()->write($judgeManagement->to_json());
		return $response->withStatus($judgeManagement->get_http_code());
	}
}