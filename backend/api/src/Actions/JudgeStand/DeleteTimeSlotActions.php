<?php
namespace App\Actions\JudgeStand;

use App\Services\JudgeStandService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;


/**
 * Action qui permet de supprimer le dernier time-slot (plage horaire).
 * @author Alexis Boivin, inspiré des codes existants.
 */
class DeleteTimeSlotActions
{
    private $standService;
	private $tokenService;

	public function __construct(JudgeStandService $judgeStandService, TokenService $tokenService)
	{
		$this->standService = $judgeStandService;
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
		$result = $this->standService->delete_time_slot();
        $response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}