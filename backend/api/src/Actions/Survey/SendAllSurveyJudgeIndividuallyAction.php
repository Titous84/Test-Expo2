<?php

namespace App\Actions\Survey;

use App\Services\SurveyService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * SendAllSurveyJudgeIndividuallyAction
 * @author Tommy Garneau
 * @package App\Actions\Survey
 */
class SendAllSurveyJudgeIndividuallyAction
{
    /**
     * @var SurveyService Permet d'avoir accès à la classe SurveyService
     */
    private $surveyService;

	/**
	 * Constructeur. SendAllSurveyJudgeIndividuallyAction.
	 * @param SurveyService $surveyService Service permettant d'obtenir les évaluations.
	 */
    public function __construct(SurveyService $surveyService)
    {
		$this->surveyService = $surveyService;
    }
	/**
	 * Fonction invoquée lors de l'appel de la classe SendAllSurveyJudgeIndividuallyAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $resultEmailSentJudge = $this->surveyService->send_all_survey_judgeIndividually($parsedBody);
        $response->getBody()->write($resultEmailSentJudge->to_json());
        return $response->withStatus($resultEmailSentJudge->get_http_code());
    }

}