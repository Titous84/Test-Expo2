<?php

namespace App\Actions\Stand;

use App\Enums\EnumHttpCode;
use App\Services\StandService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;

/**
 * Classe permettant de gérer la page par défaut.
 * Ca prend un body pour que la requete marche 
 * exemple de body: {"judge_name":"le testeur professionnel","stand":5}
 * @author Alex Des Ruisseaux
 */
class ConflictStandAction
{
	/**
	 * StandService
	 *
	 * @var StandService Permet d'avoir assez à la classe StandService
	 * @var tokenService Permet d'avoir assez à la classe tokenService
	 */
	private $standService;
	private $tokenService;

    public function __construct(StandService $standService,TokenService $tokenService)
    {
		$this->standService = $standService;
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

		$result = $this -> standService->conflict_stand($request->getParsedBody());
		$response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}