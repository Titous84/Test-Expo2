import APIResult from "../../types/apiResult";
import JugeInfo from "../../types/juge-stand/jugeInfo";
import StandInfo from "../../types/juge-stand/standInfo";
import { ShowToast } from "../../utils/utils";
import { APIRequest } from "../apiUtils";
import IAssignation from '../../types/juge-stand/IAssignation';
import TimeSlots from "../../types/juge-stand/timeSlots";
import { ICategories } from "../../types/TeamsList/ICategories";
import IEvaluationId from "../../types/juge-stand/IEvaluationId";


/**
 * Classe contenant les appels d'API en lien avec la connexion.
 * @author Souleymane Soumaré
 */
export default class JugeStandService {
    /**
     *Stand
     * @author Souleymane Soumaré
     * @return {StandInfo} Le retour de l'API contenant les stands.
     */
    public static async GetStand(): Promise<APIResult<StandInfo[]>> {
        const response: APIResult<StandInfo[]> = await APIRequest("juge-stand/stand", "GET", true)
        return response;
    }
    /**
     * Juges
     * @author Souleymane Soumaré <adresseDev>
     * 
     * @return {JugeInfo}  Il retourne un tableau des juges
     */

    public static async GetJudge(): Promise<APIResult<JugeInfo[]>> {

        const response: APIResult<JugeInfo[]> = await APIRequest("juge-stand/juge", "GET", true)
        return response;
    }
    /**
     * route pour ajouter une heure d'évaluation a un stand
     * @param heure id de l'heure de l'evaluation.
     * @param judgeId Id du juge.
     * @param standId Id du stand.
     * @param surveyId Id du survey.
     * @returns 
     * @author Alex Des Ruisseaux
     */
    public static async AddTimeStand(hour: number, judgeId: number, standId: number, surveyId: number): Promise<APIResult<boolean>> {
        let body = {
            'hour': hour,
            'judge_id': judgeId,
            'stand_id': standId,
            'survey_id': surveyId
        }
        const response : APIResult<boolean> = await APIRequest("stand/insert-time","POST",true, body)
        return response;
    }
/**
 * Check s'il y a un conflit d'interet (connaissance, famille, etc ) entre le juge et l'un des membres du stand
 * @param {string} judge_name - nom complet du juge
 * @param {number} stand - numero du stand
 * @return {boolean}  retourne un booleen.
 * @author Alex Des Ruisseaux, Souleymane Soumaré
 * @author Alex Des Ruisseaux, Souleymane Soumaré
 */
    public static async GetConflict(judge_name: string, stand: string): Promise<boolean> {
        const response: APIResult<boolean> = await APIRequest("stand/conflits", "POST", true, { judge_name, stand })
        if (response.data === false) {
            ShowToast("Il ya un conflit d'interet entre ce juge et cette équipe",5000,"error","top-center", false)
            return false
        }
        return true;
    }

    /**
     * @author Déreck "The GOAT" Lachance
     * @description Permet d'obtenir les plages horaires des stands.
     * @returns Retourne des strings contenant les plages horaires.
     */
    public static async GetAllTimeSlots(): Promise<APIResult<TimeSlots[]>> {
        const response: APIResult<TimeSlots[]> = await APIRequest("juge-stand/get-time-slots", "GET", true)
        return response;
    }


    /**
     * @author Déreck "The GOAT" Lachance
     * @description Permet d'insérer les évaluations d'un stand.
     * @param standId L'id du stand.
     * @param jugeId L'id du juge.
     * @param surveyId type d'évaluation. (survey_id)
     * @param heure L'heure de l'évaluation.
     * @returns un boolean pour savoir si l'opération a réussi.
     */
    public static async PushStandSurvey(judge_id: number, stand_id: string, survey_id: number, heure: number): Promise<APIResult<number>> {
        const body = {
            judge_id,
            stand_id,
            survey_id,
            heure,
        }
        return await APIRequest("juge-stand/evaluation", "POST", true, body)
    }
    public static async PatchStandSurvey(id: number, stand_id: string, survey_id: number) {
        const body = {
            id,
            stand_id,
            survey_id,
        }

        return await APIRequest("juge-stand/evaluation", "PATCH", true, body);
    }

    /**
    * @author Xavier Houle
    * @description Permet de suprrimer une évaluation d'un stand
    * @param standId
    * @param jugeId
    * @param heure
    */
    public static async DeleteSurvey(id: number) {
        const response: APIResult<boolean> = await APIRequest(`juge-stand/evaluation/${id}`, "DELETE", true);

        return response;
    }


    /**
     * @author Christopher Boisvert
     * @description Permet d'obtenir tous les ids et noms des evaluations.
     * @returns Retourne un tableau contenant les formulaires de type ISurvey.
     */
    public static async GetAllEvaluations(): Promise<APIResult<IAssignation[]>> {
        const response: APIResult<IAssignation[]> = await APIRequest("stand/get-survey", "GET", true)

        return response;
    }

    private static dateToSQLDate(date: Date) {
        const hours = date.getHours().toString().padStart(2, "0");
        const minutes = date.getMinutes().toString().padStart(2, "0");
        const seconds = date.getSeconds().toString().padStart(2, "0");

        return `${hours}:${minutes}:${seconds}`;
    }


    /**
     * @author Xavier Houle
     * @description Permet d'enregistrer les nouvelles heures de passages
     * @returns Retourne un booléen permettant de savoir si la requète à fonctionner
     */
    public static async SaveAllTimeSlots(hours: TimeSlots[]) : Promise<APIResult<boolean>> {
        const formattedDate = hours.map(slot => ({
            id: slot.id,
            time: this.dateToSQLDate(slot.time)
        }));

        const body = {
            hours: formattedDate
        };

        return await APIRequest("juge-stand/update-time-slots", "PUT", true, body);
        
    }


    /**
     * @author Alexis Boivin
     * @description Permet d'ajouter une plage horaire pour les heure de passage.
     * @returns Retourne un booléen permettant de savoir si la requète à fonctionner
     */
        public static async AddTimeSlot(nouveauSlot: TimeSlots) : Promise<APIResult<boolean>> {
            const formattedDate = {
                id: nouveauSlot.id,
                time: this.dateToSQLDate(nouveauSlot.time)
            };
    
            const body = {
                hours: [formattedDate]
            };
            return await APIRequest("juge-stand/add-time-slot", "POST", true, body);
            
        }

    /**
     * @author Alexis Boivin
     * @description Permet de supprimer une plage horaire pour les heure de passage.
     * @returns Retourne un booléen permettant de savoir si la requète à fonctionner
     */
    public static async DeleteTimeSlot() : Promise<APIResult<boolean>> {
        return await APIRequest("juge-stand/delete-time-slot", "DELETE", true);      
    }
}
