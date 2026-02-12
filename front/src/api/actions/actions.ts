import APIResult from "../../types/apiResult";
import SendEvaluation from "../../types/JudgeEvaluation/sendEvaluation";
import { APIRequest } from "../apiUtils";
import Judge from "../../types/judge";

export default class Actions{

    /**
     * Obtien une réponse à la suite d'envoi des courriels d'évaluations aux juges
     * @returns SendEvaluation[]
     * @author Charles Lavoie
     */
    public static async trySendEvaluation() : Promise<APIResult<SendEvaluation[]>>{
       
        const response : APIResult<SendEvaluation[]> = await APIRequest("evaluation/send","GET",false);
        return response;
    }

    /**
     * Obtien une réponse à la suite d'envoi du courriel à un seul juge
     * @param judges Liste de juges à qui envoyer le courriel
     * @returns SendEvaluation[]
     * @author Tommy Garneau
     */
    public static async trySendEvaluationIndividually(judges: Judge[]): Promise<APIResult<SendEvaluation[]>> {
        const response: APIResult<SendEvaluation[]> = await APIRequest("evaluation/sendIndividually", "POST", true, judges);
        return response;
    }
}