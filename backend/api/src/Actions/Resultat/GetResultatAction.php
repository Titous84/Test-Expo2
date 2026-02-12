<?php

namespace App\Actions\Resultat;

use App\Services\ResultatService;
use Psr\Http\Message\ResponseInterface;
use App\Enums\EnumHttpCode;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;
/**
 * Classe permettant d'obtenir un tableau de resultat.
 * @author Souleymane Soumaré <adresseDev>

 */
class GetResultatAction
{
	private $resultatService;
	private $tokenService;

	public function __construct(ResultatService $resultatService,TokenService $tokenService)
	{
		$this->resultatService = $resultatService;
		$this->tokenService = $tokenService;
	}

	/**
	 * Fonction invoquée lors de l'appelle de la classe GetResultatAction.
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
		$result = $this->resultatService->showingResultat();
		
		$response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}