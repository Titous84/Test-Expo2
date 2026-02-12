import APIResult from "../../types/apiResult";
import Category from "../../types/sign-up/category";
import Judge from "../../types/judge";
import { APIRequest } from "../apiUtils";

/**
 * API pour la page d'inscription d'une juge
 * @author Jean-Philippe Bourassa
 */
export default class SignUpJudgeService{

    /**
     * Obtient les catégories
     * @returns Category[]
     */
    public static async tryGetCategory() : Promise<APIResult<Category[]>>{
        const response : APIResult<Category[]> = await APIRequest("signup/category","GET",false)
        return response;
    }
    
    /**
     * Obtient le juge par son activation_token
     * @param token activation_token du juge
     * @returns Judge
     */
    public static async tryGetJudge(token: string) : Promise<APIResult<Judge>>{
       
        const response : APIResult<Judge> = await APIRequest(`judge/${token}`,"GET",false)
        return response;
    }

    /**
     * Inscrit la partie utilisateur du juge
     * @param judge Judge
     * @returns Judge
     */
    public static async tryPostUser(judge:Judge) : Promise<APIResult<Judge>>{
        const response : APIResult<Judge> = await APIRequest("judge/user","POST",false,judge)
        return response;
    }
    
    /**
     * Inscrit la partie juge du juge
     * @param judge Judge
     * @returns Judge
     */
    public static async tryPostJudge(judge:Judge) : Promise<APIResult<Judge>>{
        const body = {
            judge
        }
        const response : APIResult<Judge> = await APIRequest("judge/judge","POST",false,body)
        return response;
    }
}