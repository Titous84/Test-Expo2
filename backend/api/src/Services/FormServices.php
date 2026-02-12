<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Repositories\FormRepository;
use Exception;

/**
 * Class FormServices
 * @author Tomy Chouinard
 * @package App\Services
 * @deprecated Utiliser EvaluationGridService à la place.
 */
class FormServices
{
    /**
     * @var Dépôt lié à la bd permettant d'accéder aux sections.
     */
    private $formRepository;

    /**
     * Form constructeur.
     * @param FormRepository $formRepository Dépôt du formulaire.
     */
    public function __construct(FormRepository $_formRepository)
    {
        $this->formRepository = $_formRepository;
    }

    // ##################################################################### QUESTIONS #################################################################################################  //


    /**
     * Fonction permettant d'obtenir les formulaires.
     * @return Result Retourne le résultat de l'opération.
     */
    public function get_all_questions($id): Result
    {
        try {
            $resultGetAllQuestion = $this->formRepository->get_all_criteria($id);

            if ($resultGetAllQuestion == null) {
                return new Result(EnumHttpCode::NOT_FOUND, array("Aucune question n'a été trouvé !"));
            }

            return new Result(EnumHttpCode::SUCCESS, array(""), $resultGetAllQuestion);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des questions."));
        }
    }

    public function create_question(string $name,int $position,int $rating_section_id, int $maxValue, int $increment):Result{
        try {
            $resultCreateQuestion = $this->formRepository->create_question($name,$position,$rating_section_id,$maxValue,$increment);

            if ($resultCreateQuestion == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La création du critère a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La création du critère a été un franc succès!"), $resultCreateQuestion);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la création du critère." . $exception));
        }
    }

    public function deleteQuestion($id):Result{
        $resultDeleteSurvey = $this->formRepository->deleteQuestion($id);
        try{
            if ($resultDeleteSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La suppression de la question a échoué!"), null);
            }
            return new Result(EnumHttpCode::CREATED, array("La suppression de la question a été un franc succès!"), $resultDeleteSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la suppression de la question." . $exception));
        }
    }

    public function update_question(int $id, string $name,int $position,int $maxValue,int $increment):Result{
        try {
            $resultUpdateSurvey = $this->formRepository->update_question($id,$name,$position,$maxValue,$increment);

            if ($resultUpdateSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La modification de la question a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La modification de la question a été un franc succès!"), $resultUpdateSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la modification de la question." . $exception));
        }
    }

    // ##################################################################### SECTIONS #################################################################################################  //

    /**
     * Fonction permettant d'obtenir les sections.
     * @return Result Retourne le résultat de l'opération.
     */
    public function get_all_sections($id): Result
    {
        try {
            $resultGetAllSurvey = $this->formRepository->get_all_sections($id);

            if ($resultGetAllSurvey == null) {
                return new Result(EnumHttpCode::NOT_FOUND, array("Aucune section n'a été trouvé !"));
            }

            return new Result(EnumHttpCode::SUCCESS, array(""), $resultGetAllSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des sections."));
        }
    }

    public function create_section(string $name,int $position, int $survey_id):Result{
        try {
            $resultCreateSection = $this->formRepository->create_section($name,$position,$survey_id);

            if ($resultCreateSection == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La création de la section a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La création de la section a été un franc succès!"), $resultCreateSection);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la création de la section." . $exception));
        }
    }

    public function deleteSection($id):Result{
        $resultDeleteSurvey = $this->formRepository->deleteSection($id);
        try{
            if ($resultDeleteSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La suppression de la section a échoué!"), null);
            }
            return new Result(EnumHttpCode::CREATED, array("La suppression de la section a été un franc succès!"), $resultDeleteSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la suppression de la section." . $exception));
        }
    }

    public function update_section(int $id, string $name,int $position, int $survey_id):Result{
        try {
            $resultUpdateSurvey = $this->formRepository->update_section($id,$name,$position,$survey_id);

            if ($resultUpdateSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La modification de la section a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La modification de la section a été un franc succès!"), $resultUpdateSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la modification de la section." . $exception));
        }
    }

    // ##################################################################### SURVEY #################################################################################################  //

    /**
     * Fonction permettant d'obtenir les formulaires.
     * @return Result Retourne le résultat de l'opération.
     */
    public function get_all_survey(): Result
    {
        try {
            $resultGetAllSurvey = $this->formRepository->get_all_survey();

            if ($resultGetAllSurvey == null) {
                return new Result(EnumHttpCode::NOT_FOUND, array("Aucun formulaire n'a été trouvé !"));
            }

            return new Result(EnumHttpCode::SUCCESS, array(""), $resultGetAllSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'obtention des formulaires."));
        }
    }

    public function create_survey(string $name):Result{
        try {
            $resultCreateSurvey = $this->formRepository->create_survey($name);

            if ($resultCreateSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La création du formulaire a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La création du formulaire a été un franc succès!"), $resultCreateSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la création du formulaire." . $exception));
        }
    }

    public function deleteSurvey($id):Result{
       $resultDeleteSurvey = $this->formRepository->deleteSurvey($id);
        try{
            if ($resultDeleteSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La suppression du formulaire a échoué!"), null);
            }
            return new Result(EnumHttpCode::CREATED, array("La suppression du formulaire a été un franc succès!"), $resultDeleteSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la suppression du formulaire." . $exception));
        }
    }

    public function update_survey(int $id, string $name, int $survey_id):Result{
        try {
            $resultUpdateSurvey = $this->formRepository->update_survey($id,$name,$survey_id);

            if ($resultUpdateSurvey == null)
            {
                return new Result(EnumHttpCode::SERVER_ERROR, array("La modification du formulaire a échoué!"), null);
            }

            return new Result(EnumHttpCode::CREATED, array("La modification du formulaire a été un franc succès!"), $resultUpdateSurvey);
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la modification du formulaire." . $exception));
        }
    }

}
?>
