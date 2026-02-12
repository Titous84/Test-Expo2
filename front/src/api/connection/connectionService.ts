import APIResult from "../../types/apiResult";
import UserRole from "../../types/user/userRole";
import { APIRequest } from "../apiUtils";

/**
 * Classe contenant les appels d'API en lien avec la connexion.
 */
export default class ConnectionService{
    /**
     * Essaie d'avoir un jeton pour pouvoir se connecter.
     * @author Mathieu Sévégny
     * @param email Courriel donné par l'usager.
     * @param password Mot de passe donné par l'usager.
     * @returns Le retour de l'API contenant peut-être le jeton.
     */
    public static async tryGetToken(email:string,password:string) : Promise<APIResult<string>>{
        const body = {
            email,
            password
        }
        const response : APIResult<string> = await APIRequest("token","POST",false,body)

        return response;
    }

    public static async tryGetUserRole() : Promise<APIResult<UserRole>>{
        const response : APIResult<UserRole> = await APIRequest(`user/role`,"GET",true)
        return response;
    }
}