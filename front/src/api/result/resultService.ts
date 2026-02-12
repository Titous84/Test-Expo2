import APIResult from '../../types/apiResult';
import ResultInfo from "../../types/results/resultInfo";
import { APIRequest } from "../apiUtils";

/**
 * Classe contenant les appels d'API en lien avec la connexion.
 */
export default class ResultService{
    /**
     *Resultat des juges.
     * @author Souleymane Soumaré
     * @returns Le retour de l'API contenant les resultats.
     */
    public static async GetResult() : Promise<APIResult<ResultInfo[]>>{
        const response : APIResult<ResultInfo[]> = await APIRequest("resultat","GET",true)
        return response;
    }

// Envoi du mail a la personne ressource
    public static async GetSendResult(email_ressource_person:string,name_ressource_person:string,team_name:string,note:number): Promise<APIResult<ResultInfo[]>>{
        
        const response : APIResult<ResultInfo[]> = await APIRequest("envoi-resultat","POST",true,{email_ressource_person,name_ressource_person,team_name,note})
        return response;
    }

    /**
       * Permet de supprimer le résultat d'un juge pour une équipe.
       * @param teamName Nom de l'équipe.
       * @param judgeId ID du juge.
       * @returns string
       */
      public static async deletesJudgeScore(teamName: string, judgeId: number): Promise<APIResult<string>> {
        try {
            const body = { teamName, judgeId };
            const response: APIResult<string> = await APIRequest(
                "supprimer-resultat",
                "DELETE",
                true,
                body
            );
            return response;
        } catch (error) {
            return { data: undefined, error: "Une erreur est survenue lors de la suppression des résultats." };
        }
      }

    /**
     * Récupère les états d'exclusion des scores des juges.
     * 
     * @author Francis Payan
     * @returns Le retour de l'API contenant les états d'exclusion.
     */
    public static async getScoreExclusions(): Promise<APIResult<{ [teamName: string]: { [judgeName: string]: boolean } }>> {
        const response = await APIRequest<{ [teamName: string]: { [judgeName: string]: boolean } }>("evaluation/get-global_score_removed", "GET", true);
        return response;
    }


    /**
     * Met à jour l'exclusion d'un score pour un juge spécifique.
     * 
     * @param judge_id L'ID du juge.
     * @param isExcluded État d'exclusion à appliquer.
     * @returns Le résultat de l'appel API.
     */
    public static async updateScoreExclusion(judge_id: number, isExcluded: boolean): Promise<APIResult<any>> {
        const requestBody = {
            "global_score_removed" : isExcluded,
        };
        const response: APIResult<any> = await APIRequest("evaluation/update-score-exclusion/" + judge_id, "PATCH", true, requestBody);
        return response;
    }
}