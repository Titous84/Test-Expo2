<?php

namespace App\Actions\TeamsList;

use App\Services\TeamsListService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Models\Result;
use App\Enums\EnumHttpCode;
use App\Services\TokenService;

/**
 * Classe permettant d'ajouter un membre d'équipe.
 * @author Carlos Cordeiro
 * @package App\Actions\TeamsList
 */
class AddTeamMember
{
    /**
     * Service de la liste d'équipes
     */
    private $TeamsListService;
    /**
     * Service pour le token
     */
	private $tokenService;

    /**
     * Constructeur de la classe
     */
    public function __construct(TeamsListService $TeamsListService, TokenService $tokenService)
	{
		$this->TeamsListService = $TeamsListService;
		$this->tokenService = $tokenService;
	}

    /**
     * inspiré de get AddUserAction
	 * Fonction invoquée lors de l'appel de la classe AddTeamMember.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}
		$body = $request->getParsedBody();
		$resultAddMember = $this->TeamsListService->add_team_member($body);
		$response->getBody()->write($resultAddMember->to_json());
		return $response->withStatus($resultAddMember->get_http_code());
	}
}