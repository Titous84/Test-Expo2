<?php

namespace App\Actions\Survey;

use App\Services\SurveyService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * CloseSurveyAction
 * @author Christopher Boisvert
 * @package App\Actions\Survey
 */
class CloseSurveyAction
{
    /**
     * @var SurveyService Permet d'avoir assez à la classe SurveyService
     */
    private $surveyService;

	/**
	 * Constructeur. CloseSurveyAction.
	 * @param SurveyService $surveyService Service permettant d'obtenir les évaluations.
	 */
    public function __construct(SurveyService $surveyService)
    {
		$this->surveyService = $surveyService;
    }
    
	/**
	 * Fonction invoquée lors de l'appel de la classe CloseSurveyAction.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, $args): ResponseInterface
	{
		$resultSurveyScore = $this->surveyService->close_survey($args);
        $response->getBody()->write($resultSurveyScore->to_json());
		return $response->withStatus($resultSurveyScore->get_http_code());
	}
}