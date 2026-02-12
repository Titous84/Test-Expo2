import APIResult from "../../types/apiResult";
import Category from "../../types/sign-up/category";
import TeamInfo from "../../types/sign-up/team-info";
import { APIRequest } from "../apiUtils";

/**
 * API pour la page d'inscription d'une équipe
 * @author Tristan Lafontaine
 */
export default class SignUpService{

    /**
     * Obtien les catégories
     * @returns Category[]
     */
    public static async tryGetCategory() : Promise<APIResult<Category[]>>{
       
        const response : APIResult<Category[]> = await APIRequest("signup/category","GET",false)
        return response;
    }

    /**
     * Inscription d'une équipe
     * @param team TeamInfo Les informations de l'équipe
     * @returns 
     */
    public static async tryPostTeam(team:TeamInfo) : Promise<APIResult<TeamInfo>>{
        const body = {
            team
        }
        const response : APIResult<TeamInfo> = await APIRequest("signup/", "POST", false, body)
        return response;
    }
}