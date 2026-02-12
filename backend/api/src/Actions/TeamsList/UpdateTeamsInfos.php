<?php

namespace App\Actions\TeamsList;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TeamsListService;
use App\Utils\TokenUtils;
use App\Services\TokenService;

/**
 * Classe permettant de gérer la page par défaut.
 * @author Tristan Lafontaine
 * @package App\Actions\TeamsList
 */
class UpdateTeamsInfos
{	

	/**
	 * @var TeamsListService Dépôt liée à la classe TeamListService
	 */
	private $teamsListService;
	
	/**
	 * @var TokenService Dépôt liée au service TokenService
	 */
	private $tokenService;
	
	/**
     * Constructeur de la classe DeleteTeamsMembers
     *
     * @param  TeamsListService $teamsListService
     * @param  TokenService $tokenService
     * @return void
     */
    public function __construct(TeamsListService $teamsListService, TokenService $tokenService)
    {
		$this->teamsListService = $teamsListService;
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
		try {
			$data = $request->getParsedBody();
	
			$result = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin"]);
	
			if ($result != null) {
				$response->getBody()->write($result->to_json());
				return $response->withStatus($result->get_http_code());
			}
	
			$teamsList = $this->teamsListService->update_teams_infos($data);
			$response->getBody()->write($teamsList->to_json());
			return $response->withStatus($teamsList->get_http_code());
		} catch (Exception $e) {
			return $response->withStatus(500);
		}
	}
}