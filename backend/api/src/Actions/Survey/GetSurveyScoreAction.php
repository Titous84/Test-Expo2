<?php

namespace App\Actions\Survey;

use App\Enums\EnumHttpCode;
use App\Services\SurveyService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * GetSurveyScoreAction
 * @author Christopher Boisvert
 * @package App\Actions\Survey
 */
class GetSurveyScoreAction
{
    /**
     * @var SurveyService Permet d'avoir assez à la classe SurveyService
     */
    private $surveyService;

	/**
	 * Constructeur. GetSurveyScoreAction.
	 * @param SurveyService $surveyService Service permettant d'obtenir les évaluations.
	 */
    public function __construct(SurveyService $surveyService)
    {
		$this->surveyService = $surveyService;
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
		$resultSurveyScore = $this->surveyService->get_survey_score($args);
        $response->getBody()->write($resultSurveyScore->to_json());
		return $response->withStatus($resultSurveyScore->get_http_code());
	}
}