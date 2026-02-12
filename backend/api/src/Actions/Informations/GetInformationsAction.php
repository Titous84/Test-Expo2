<?php

namespace App\Actions\Informations;

use App\Services\InformationsService;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;

/**
 * Classe permettant d'obtenir les informations du site.
 */
class GetInformationsAction
{
	private $informationService;
	private $tokenService;

	public function __construct(InformationsService $informationService,TokenService $tokenService)
	{
		$this->informationService = $informationService;
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

        $result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);


         if ($result !== null){
            $informations = $this->informationService->get_informations();
            $response->getBody()->write($informations->to_json());
            return $response->withStatus($informations->get_http_code());
         }
         else{
            $informations = $this->informationService->get_informations_admin();
            $response->getBody()->write($informations->to_json());
            return $response->withStatus($informations->get_http_code());
         }

		
	}
}