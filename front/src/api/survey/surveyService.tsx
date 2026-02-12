import APIResult from "../../types/apiResult";
import { IEvaluation } from "../../types/survey/IEvaluation";
import { APIRequest } from "../apiUtils";

/**
 * @author Christopher Boisvert
 *  API pour obtenir les formulaires d'évaluations.
 */
export default class SurveyService
{
    /**
     * @author Christopher Boisvert
     *  Permet d'obtenir tous les formulaires associés à un juge par son UUID.
     * @returns Retourne un tableau contenant les formulaires de type ISurvey.
     */
    public static async getAllSurveyByJudgeUUID(judgeUUID:string) : Promise<APIResult<IEvaluation[]>>{
       
        const response : APIResult<IEvaluation[]> = await APIRequest("evaluation/judge/" + judgeUUID, "GET", true)
        return response;
    }

    /**
     * @author Christopher Boisvert
     *  Permet de configurer le score d'une question.
     * @param score Score de la question.
     * @param evaluationID Id de l'évaluation.
     * @param criteriaID Id du critère.
     * @returns Retourne void.
     */
    public static async setScoreOfSurveyQuestion(score:number, evaluationID:number, criteriaID: number): Promise<APIResult<void>>{
        const body = {
            "score": score,
            "evaluation_id": evaluationID,
            "criteria_id": criteriaID
        }
        const response : APIResult<void> = await APIRequest("evaluation/question/result", "POST", false, body)
        return response;
    }

    /**
     * @author Jean-Christophe Demers
     *  Permet de configurer le commentaire d'un questionnaire.
     * @param comment Commentaire du questionnaire.
     * @param evaluationID Id de l'évaluation.
     * @returns Retourne void.
     */
    public static async setCommentOfSurvey(comment:string, evaluationID:number): Promise<APIResult<void>>{
        const body = {
            "comment": comment,
            "evaluation_id": evaluationID,
        }
        const response : APIResult<void> = await APIRequest("evaluation/comment", "POST", false, body)
        return response;
    }

    /**
     * @author Christopher Boisvert
     *  Permet d'obtenir le score d'une évaluation.
     * @param surveyId Id de l'évaluation.
     * @returns Retourne le score de l'évaluation.
     */
    public static async getSurveyScore(surveyId:number):Promise<APIResult<number>>
    {
        const response : APIResult<number> = await APIRequest("evaluation/score/" + surveyId, "GET", false)
        return response;
    }

    /**
     * @author Christopher Boisvert
     *  Permet de fermer définitivement un formulaire.
     * @param surveyId Id de l'évaluation.
     * @returns 
     */
    public static async closeSurvey(surveyId:number):Promise<APIResult<void>>
    {
        const response : APIResult<void> = await APIRequest("evaluation/close/" + surveyId, "GET", false)
        return response;
    }
}