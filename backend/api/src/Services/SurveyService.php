<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Handlers\LogHandler;
use App\Repositories\SurveyRepository;
use App\Models\Result;
use App\Models\SurveyQuestionResult;
use App\Utils\GeneratorUUID;
use App\Validators\ValidatorQuestionResult;
use App\Validators\ValidatorUUID;
use App\Fabricators\Emails\EmailEvaluationFabricator;
use App\Models\SurveyCommentResult;
use App\Validators\ValidatorCommentResult;
use Exception;

/**
 * Classe SurveyService permet d'obtenir les formulaires d'évaluation.
 * @author Christopher Boisvert
 * @package App\Services
 */
class SurveyService
{
    /**
     * @var SurveyRepository $surveyRepository Repositoire des évaluations.
     */
    private $surveyRepository;

    /**
     * @var LogHandler $logHandler Permet d'enregistrer des logs.
     */
    private $logHandler;

    /**
     * @var ValidatorUUID $validatorUUID Permet de vérifier un UUID.
     */
    private $validatorUUID;

    /**
     * @var ValidatorCommentResult $validatorCommentResult Permet de vérifier un résultat d'une question.
     */
    private $validatorCommentResult;

    /**
     * @var ValidatorQuestionResult $validatorQuestionResult Permet de vérifier un résultat d'une question.
     */
    private $validatorQuestionResult;

    /**
     * @var EmailEvaluationFabricator $emailEvaluationFabricator Permet d'envoyer des courriels contenant un lien vers les évaluations.
     */
    private $emailEvaluationFabricator;

    /**
     * SurveyService constructeur.
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        LogHandler $logHandler,
        ValidatorUUID $validatorUUID,
        ValidatorCommentResult $validatorCommentResult,
        ValidatorQuestionResult $validatorQuestionResult,
        EmailEvaluationFabricator $emailEvaluationFabricator
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->logHandler = $logHandler;
        $this->validatorUUID = $validatorUUID;
        $this->validatorCommentResult = $validatorCommentResult;
        $this->validatorQuestionResult = $validatorQuestionResult;
        $this->emailEvaluationFabricator = $emailEvaluationFabricator;
    }

    /**
     * Fonction qui permet d'obtenir toutes les formulaires assignés à un juge par son uuid.
     * @param string $judge_uuid UUID du juge.
     * @return Result Retourne le résultat de l'opération de recherche des formulaires d'évaluations.
     */
    public function get_all_survey_by_judge_id(string $judgeUUID): Result
    {
        try {
            $resultValidatorUUID = $this->validatorUUID->validate($judgeUUID);

            if ($resultValidatorUUID->get_http_code() != EnumHttpCode::SUCCESS)
                return $resultValidatorUUID;

            $listSurvey = $this->surveyRepository->get_all_survey_by_judge_id($judgeUUID);

            if (count($listSurvey) === 0) {
                return new Result(EnumHttpCode::NOT_FOUND, array("Aucune évaluation trouvé."), $listSurvey);
            }

            foreach ($listSurvey as $key => $survey) {
                $surveyId = $survey["survey_id"];
                $evaluationId = $survey["id"];

                unset($listSurvey[$key]["survey_id"]);

                $listSurvey[$key]["score"] = $this->surveyRepository->get_survey_score($survey["id"]) ?: 0;

                $listSection = $this->surveyRepository->get_all_sections_by_survey_id($surveyId);

                foreach ($listSection as $key2 => $section) {
                    $listQuestion = $this->surveyRepository->get_all_questions_by_section_id_and_evaluation_id($section["id"]);

                    foreach ($listQuestion as $key3 => $question) {
                        $listQuestion[$key3]["score"] =
                            $this->surveyRepository->get_question_result_by_evaluation_id_and_criteria_id(
                                new SurveyQuestionResult(array("evaluation_id" => $evaluationId, "criteria_id" => $question["id"]))
                            )
                            ?: 0;
                    }

                    $listSection[$key2]["questions"] = $listQuestion;
                }

                $listSurvey[$key]["sections"] = $listSection;
            }

            return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouvés des formulaires"), $listSurvey);
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
	 * @author Jean-Christophe Demers
     * Fonction qui permet d'ajouter une évaluation d'un commentaire d'un juge.
     * @param mixed $commentResultJSON Données bruts de la réponse du juge.
     * @return Result Retourne un résultat de l'opération d'ajout de l'évaluation du juge.
     */
    public function set_comment_result($commentResultJSON): Result
    {
        try {
            $resultValidationCommentResult = $this->validatorCommentResult->validate($commentResultJSON);

            if ($resultValidationCommentResult->get_http_code() != EnumHttpCode::SUCCESS)
                return $resultValidationCommentResult;

            if ($this->surveyRepository->set_comment_result(new SurveyCommentResult($commentResultJSON)) > 0)
                return new Result(EnumHttpCode::SUCCESS, array("Le résultat de cette question a bel et bien été mis à jour !"), true);

            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue a empêché la mise à jour de la question."), false);
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet d'ajouter une évaluation d'une question d'un juge.
     * @param mixed $questionResultJSON Données bruts de la réponse du juge.
     * @return Result Retourne un résultat de l'opération d'ajout de l'évaluation du juge.
     */
    public function set_question_result($questionResultJSON): Result
    {
        try {
            $resultValidationQuestionResult = $this->validatorQuestionResult->validate($questionResultJSON);

            if ($resultValidationQuestionResult->get_http_code() != EnumHttpCode::SUCCESS)
                return $resultValidationQuestionResult;

            if ($this->surveyRepository->add_or_replace_question_result(new SurveyQuestionResult($questionResultJSON)) > 0)
                return new Result(EnumHttpCode::SUCCESS, array("Le résultat de cette question a bel et bien été mis à jour !"));

            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue a empêché la mise à jour de la question."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet d'obtenir le score d'une question.
     * @param mixed $evaluationGetSurveyScoreJSON Tableau contenant les informations afin d'obtenir le score d'une évaluation.
     * @return Result Retourne le résultat de recherche du score d'une évaluation.
     */
    public function get_survey_score($evaluationGetSurveyScoreJSON): Result
    {
        try {
            $surveyId = $evaluationGetSurveyScoreJSON["surveyId"];
            if (is_null($surveyId))
                return new Result(EnumHttpCode::BAD_REQUEST, array("Veuillez fournir l'id de l'évaluation."));
            $resultGetSurveyScore = $this->surveyRepository->get_survey_score($surveyId);
            if (!is_null($resultGetSurveyScore))
                return new Result(EnumHttpCode::SUCCESS, array("Le score a été obtenu avec succès."), ["score" => $resultGetSurveyScore]);
            return new Result(EnumHttpCode::NOT_FOUND, array("Ce formulaire n'existe pas."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction permettant de fermer un formulaire d'évaluation.
     * @param mixed $evaluationCloseJSON Tableau contenant les informations liés à la fermeture de l'évaluation.
     * @return Result Retourne le résultat de la fermeture du formulaire.
     */
    public function close_survey($evaluationCloseJSON): Result
    {
        try {
            $surveyId = $evaluationCloseJSON["surveyId"];
            if (is_null($surveyId))
                return new Result(EnumHttpCode::BAD_REQUEST, array("Veuillez fournir l'id de l'évaluation."));
            $resultCloseSurvey = $this->surveyRepository->close_survey($surveyId);
            if ($resultCloseSurvey)
                return new Result(EnumHttpCode::SUCCESS, array("Le formulaire d'évaluation a été fermé avec succès."));
            return new Result(EnumHttpCode::NOT_FOUND, array("Ce formulaire n'existe pas."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

    /**
     * Fonction qui permet d'envoyer les courriels à tous les juges.
     * @return Result Retourne un résultat d'envoi de tous les courriels.
     */
    public function send_all_survey_judge()
    {
        try {
            //On va trouver tous les juges valides
            $resultFindJudge = $this->surveyRepository->find_all_judge_not_blacklisted();

            if (count($resultFindJudge) == 0) {
                return new Result(EnumHttpCode::NOT_FOUND, array("Aucun juge valide trouvé."));
            }

            //On va leur attribuer des nouveaux uuid afin de garantir la sécurité
            $nombreObjetChange = 0;
            $newJudgeArray = [];

            foreach ($resultFindJudge as $judge) {
                $new_uuid = GeneratorUUID::generate_UUID_array(1);
                $nombreObjetChange += $this->surveyRepository->change_uuid_judge($judge["id"], $new_uuid[0]);
                $judge["uuid"] = $new_uuid[0];
                array_push($newJudgeArray, $judge);
            }

            if (count($resultFindJudge) !== $nombreObjetChange) {
                return new Result(EnumHttpCode::SERVER_ERROR, array("Un ou plusieurs juge n'ont pas eu leur code UUID changé."));
            }

            $errors = [];

            //On va envoyer tous les courriels à tous les juges et récolter les résultats.
            foreach ($newJudgeArray as $judge) {
                $result = $this->emailEvaluationFabricator->send_mail($judge["email"], $judge["first_name"], $judge["last_name"], $judge["uuid"]);
                if ($result->get_http_code() != 200) {
                    array_push($errors, $result->get_message());
                }
            }

            //On va vérifier tous les erreurs récoltées 
            if (count($errors) !== 0) {
                return new Result(EnumHttpCode::SERVER_ERROR, $errors);
            }

            return new Result(EnumHttpCode::SUCCESS, array("Tous les courriels ont été envoyés aux juges."));
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }

/**
 * Fonction qui permet d'envoyer un courriel à un ou plusieurs juges reçus du frontend.
 * @param array $judges La liste des juges.
 * @return Result Retourne un résultat d'envoi des courriels.
 */
public function send_all_survey_judgeIndividually(array $judges)
{
    try {
        if (count($judges) == 0) {
            return new Result(EnumHttpCode::NOT_FOUND, array("Aucun juge trouvé."));
        }

        //On va leur attribuer des nouveaux uuid afin de garantir la sécurité
        $nombreObjetChange = 0;
        $newJudgeArray = [];

        foreach ($judges as $judge) {
            $new_uuid = GeneratorUUID::generate_UUID_array(1);
            $nombreObjetChange += $this->surveyRepository->change_uuid_judgeIndividually($judge["id"], $new_uuid[0]);
            $judge["uuid"] = $new_uuid[0];
            array_push($newJudgeArray, $judge);
        }

        if (count($judges) !== $nombreObjetChange) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Un ou plusieurs juge n'ont pas eu leur code UUID changé."));
        }

        $errors = [];

        //On va envoyer tous les courriels à tous les juges et récolter les résultats.
        foreach ($newJudgeArray as $judge) {
            $result = $this->emailEvaluationFabricator->send_mail($judge["email"],$judge["firstName"],$judge["lastName"],$judge["uuid"]);
            if ($result->get_http_code() !== 200) {
                array_push($errors, $result->get_message());
            }
        }

        //On va vérifier tous les erreurs récoltées 
        if (count($errors) !== 0) {
            return new Result(EnumHttpCode::SERVER_ERROR, $errors);
        }

        return new Result(EnumHttpCode::SUCCESS, ["Tous les courriels ont été envoyés aux juges."]);

    } catch (Exception $e) {
        $context["http_error_code"] = $e->getCode();
        $this->logHandler->critical($e->getMessage(), $context);
        return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
    }
}


    /**
     * Fonction qui permet d'obtenir toutes les formulaires
     * @return Result Retourne le résultat de l'opération de recherche des formulaires d'évaluations.
     */
    public function get_all_evaluation(): Result
    {
        try {
            $listSurvey = $this->surveyRepository->get_all_evaluation();

            if (count($listSurvey) === 0) {
                return new Result(EnumHttpCode::SUCCESS, array("Aucune évaluation trouvé."), array("pas de données"));
            }
            return new Result(EnumHttpCode::SUCCESS, array("évaluations trouvé."), $listSurvey);
        } catch (Exception $e) {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
        }
    }
}