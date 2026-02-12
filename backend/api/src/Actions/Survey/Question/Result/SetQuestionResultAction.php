<?php

namespace App\Actions\Survey\Question\Result;

use App\Services\SurveyService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * SetQuestionResultAction
 * @author Christopher Boisvert
 * @package App\Actions\Survey\Question\Result
 */
class SetQuestionResultAction
{
    /**
     * @var SurveyService Permet d'avoir accès à la classe SurveyService.
     */
    private $surveyService;

	/**
	 * Constructeur. SetQuestionResultAction.
	 * @param SurveyService $surveyService Service permettant d'obtenir les évaluations.
	 */
    public function __construct(SurveyService $surveyService)
    {
		$this->surveyService = $surveyService;
    }
    
	/**
	 * Fonction invoquée lors de l'appel de la classe SetQuestionResultAction.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$resultSurveyByJudge = $this->surveyService->set_question_result($request->getParsedBody());
        $response->getBody()->write($resultSurveyByJudge->to_json());
		return $response->withStatus($resultSurveyByJudge->get_http_code());
	}
}