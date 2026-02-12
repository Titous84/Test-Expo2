<?php

namespace App\Models;

/**
 * Classe SurveyQuestionResult.
 * @package App\Models
 * @author Christopher Boisvert
 */
class SurveyQuestionResult
{
	/**
	 * @var float|null Score d'un formulaire d'évaluation.
	 */
	public $score;
	
	/**
	 * @var int ID de l'évaluation à cette question.
	 */
	public $evaluation_id;

	/**
	 * @var int ID de la question associé à cette évaluation du juge.
	 */
	public $criteria_id;

	/**
	 * SurveyQuestionResult constructeur.
	 * @param mixed $surveyQuestionResultJSON
	 */
    public function __construct($surveyQuestionResultJSON)
    {
        $this->score = isset($surveyQuestionResultJSON["score"]) ? $surveyQuestionResultJSON["score"] : null;
		$this->evaluation_id = $surveyQuestionResultJSON["evaluation_id"];
        $this->criteria_id = $surveyQuestionResultJSON["criteria_id"];
    }
}