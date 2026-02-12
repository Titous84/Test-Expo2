import APIResult from "../../types/apiResult";
import { APIRequest } from "../apiUtils";

/**
 * @auteur Maxime Demers Boucher
 * Classe contenant les appels d'API en lien avec les codes de vérification.
 */
export default class verificationCodeService{

    /**
    * Fonction qui génère un code de vérification
    * @author Maxime Demers Boucher
    * @returns {Promise<APIResult<string[]>}
    */
    public static async generateVerificationCode(email:string) :Promise<APIResult<string[]>>{   
        const body = {
            email
         } 
        const response : APIResult<string[]> = await APIRequest(`verificationCode/generate`,"POST",true,body)
        return response;
    }

    /**
    * Fonction qui supprime un code de vérification
    * @author Maxime Demers Boucher
    * @returns {Promise<APIResult<string>}
    */
    public static async deleteVerificationCode(email:string) :Promise<APIResult<string[]>>{
        const body = {
            email
         } 

        const response : APIResult<string[]> = await APIRequest(`verificationCode/delete`,'DELETE',true,body);

        return response;
    }

    /**
    * Fonction qui vérifie le code de vérification
    * @author Maxime Demers Boucher
    * @returns {Promise<APIResult<string[]>}
    */
    public static async validateVerificationCode(code:string) :Promise<APIResult<{email : string}>>{       
        const response : APIResult<{email : string}> = await APIRequest(`verificationCode/validate/${code}`,"GET",true);
        return response;
    }
}
