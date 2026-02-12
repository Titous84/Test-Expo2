<?php

namespace App\Actions\Informations;

use App\Services\InformationsService;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Enums\EnumHttpCode;

/**
 * Classe permettant de modifier l'ordre d'un bloc d'information.
 * @author Mathieu Sévégny
 */
class UpdateOrderInformationBlockAction
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
		$body = $request->getParsedBody();

		$id = $body['id'];
		if (empty($id)) {
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, 'L\'id est obligatoire.');
		}

		$order = $body['order'];
		if (empty($order)) {
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, 'L\'ordre est obligatoire.');
		}

		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}

		$information = $this->informationService->update_information_block_order($id,$order);

		$response->getBody()->write($information->to_json());
		return $response->withStatus($information->get_http_code());
	}
}