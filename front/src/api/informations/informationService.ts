import APIResult from "../../types/apiResult";
import InformationBlockInfo from "../../types/informations/informationBlockInfo";
import { APIRequest } from "../apiUtils";

/**
 * Classe contenant les appels d'API en lien avec la connexion.
 */
export default class InformationService{
    /**
     * Cherche les informations d'Expo SAT.
     * @author Mathieu Sévégny
     * @returns Le retour de l'API contenant les informations d'Expo SAT.
     */
    public static async getInformations() : Promise<APIResult<InformationBlockInfo[]>>{
        const response : APIResult<InformationBlockInfo[]> = await APIRequest("informations","GET",true)
        
        return response;
    }
    /**
     * Crée un bloc d'information.
     * @param infoBlock Bloc d'information à créer
     * @returns La réponse de l'API
     */
    public static async createInformationBlock(infoBlock:InformationBlockInfo){
        const body = {
            title:infoBlock.title,
            content:infoBlock.content,
            order:infoBlock.order
        }
        const response : APIResult<boolean> = await APIRequest("informations","POST",true,body)

        return response;
    }
    /**
     * Modifie un bloc d'information.
     * @param infoBlock Bloc d'information à modifier
     * @returns La réponse de l'API
     */
     public static async modifyInformationBlock(infoBlock:InformationBlockInfo){
        const response : APIResult<boolean> = await APIRequest("informations","PATCH",true,infoBlock)

        return response;
    }
    /**
     * Supprime un bloc d'information.
     * @param infoBlock Bloc d'information à supprimer
     * @returns La réponse de l'API
     */
     public static async deleteInformationBlock(infoBlock:InformationBlockInfo){
        const response : APIResult<boolean> = await APIRequest(`informations/${String(infoBlock.id)}`,"DELETE",true)

        return response;
    }
    /**
     * Modifie la position d'un bloc d'informations.
     * @param infoBlock Bloc d'information à déplacer.
     * @returns La réponse de l'API
     */
     public static async modifyOrderInformationBlock(infoBlock:InformationBlockInfo){
        const response : APIResult<boolean> = await APIRequest("informations/order","PATCH",true,infoBlock)

        return response;
    }
}