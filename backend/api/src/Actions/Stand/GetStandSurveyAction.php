<?php

namespace App\Actions\Stand;

use App\Enums\EnumHttpCode;
use App\Services\SurveyService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\TokenService;
use App\Utils\TokenUtils;
/**
 * GetSurveyQuestionAction
 * @author Alex Des Ruisseaux
 */
class GetStandSurveyAction
{
    /**
     * @var SurveyService Permet d'avoir assez à la classe SurveyService
	 * @var tokenService Permet d'avoir assez à la classe tokenService
     */
    private $surveyService;
	private $tokenService;

	/**
	 * Constructeur. GetSurveyQuestionAction.
	 * @param SurveyService $surveyService Service permettant d'obtenir les évaluations.
	 */
    public function __construct(SurveyService $surveyService,TokenService $tokenService)
    {
		$this->surveyService = $surveyService;
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
        $result = $this -> surveyService->get_all_evaluation();
		$response->getBody()->write($result->to_json());
		return $response->withStatus($result->get_http_code());
	}
}