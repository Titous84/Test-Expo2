import { RoleName } from "../../router/routes";
import ActivatedUser from "../../types/activatedUser";
import APIResult from "../../types/apiResult";
import IAdministratorInformation from "../../types/AdministratorsListPage/AdministratorsTable/IAdministratorInformation";
import Judge from "../../types/judge";
import JudgeUpdate from "../../types/judgeUpdate";
import { removeToken } from "../../utils/tokenUtil";
import { APIRequest } from "../apiUtils";

interface user{
    first_name: string,
    last_name: string,
    numero_da: string,
    username: string,
    email: string,
    role_id: number
    picture_consent: boolean,
    activated:boolean,
    blacklisted:number,
}

interface changePassword{
    email:string,
    pwd: string
}

interface emailMotDePasseOublier{
    email:string,
    verificationCode:string
}

/**
 * Classe contenant les appels d'API en lien avec les users.
 */
export default class UserService {
    public static async tryValidateEmail(token:string) : Promise<string>{
        token = encodeURIComponent(token);
        
        const response : APIResult<string> = await APIRequest(`user/validate-email/${token}`,"GET",false)

        return response.error!;
    }

    /**
     * @author Charles Lavoie
     */
    public static async tryGetRole(token:string) : Promise<RoleName>{
        token = encodeURIComponent(token);
        //Changer la route lorsque créé
        const response : APIResult<{id:number,name:RoleName}> = await APIRequest(`user/role`,'GET',true);

        if (!response.data){
            removeToken();
            return "Guest";
        }
        return response.data.name;
    }

    public static async getActivatedUsers() : Promise<ActivatedUser[]>{        
        const response : APIResult<ActivatedUser[]> = await APIRequest(`user/get-active`,"GET",true)
        return response.data!;
    }

    /**
     * Récupère la liste des administrateurs avec les informations suivante: id, email, isActive.
     * @returns {Promise<IAdministratorInformation[]>} - Liste des administrateurs
     * @author Antoine Ouellette
     */
    public static async getAllAdministrators() : Promise<IAdministratorInformation[]> {
        let response: APIResult<IAdministratorInformation[]>
        try {
            response = await APIRequest("administrators/all", "GET", true);
        } catch (error) {
            console.error("Erreur lors de la récupération des administrateurs :", error);
            // S'il y a une erreur, mais pas que l'API n'a pas retourné de message d'erreur
            // destiné à l'utilisateur, retourner un message d'erreur générique.
            throw new Error("Une erreur est survenue lors de la récupération des administrateurs.");
        }
        // S'il y a un message d'erreur dans la réponse,
        if (response.error) {
            // Récupérer le message d'erreur destiné à l'utilisateur que l'API a retourné.
            throw new Error(response.error);
        }

        // Sinon, retourner la liste des administrateurs.
        return response.data ?? [];
    }

    /**
     * Crée un nouvel administrateur.
     * @param email - L'email de l'administrateur à créer.
     * @param password - Le mot de passe de l'administrateur à créer.
     * @author Antoine Ouellette
     */
    public static async createAdministrator(email: string, password: string): Promise<void> {
        let response: APIResult<void>;
        try {
            response = await APIRequest("administrators", "POST", true, { "email": email, "password": password });
        } catch (error) {
            console.error("Erreur lors de la création de l'administrateur :", error);
            // S'il y a une erreur, mais pas que l'API n'a pas retourné de message d'erreur
            // destiné à l'utilisateur, retourner un message d'erreur générique.
            throw new Error("Une erreur est survenue lors de la création de l'administrateur.");
        }
        // S'il y a un message d'erreur dans la réponse,
        if (response.error) {
            throw new Error(response.error);
        }
    }

    /**
     * Supprime une liste d'administrateurs en fonction de leurs ids.
     * @param administratorsToDeleteIds - Liste des ids des administrateurs à supprimer.
     * @author Antoine Ouellette
     */
    public static async deleteAdministratorsByIds(administratorsToDeleteIds: number[]) : Promise<void> {
        let response: APIResult<void>;
        try {
            response = await APIRequest("administrators", "DELETE", true, { ids: administratorsToDeleteIds });
            
        } catch (error) {
            console.error("Erreur lors de la suppression des administrateurs :", error);
            // S'il y a une erreur, mais pas que l'API n'a pas retourné de message d'erreur
            // destiné à l'utilisateur, retourner un message d'erreur générique.
            throw new Error("Une erreur est survenue lors de la suppression des administrateurs.");
        }
        // S'il y a un message d'erreur dans la réponse,
        if (response.error) {
            // Récupérer le message d'erreur destiné à l'utilisateur que l'API a retourné.
            throw new Error(response.error);
        }
    }

    /**
     * @author Thomas-gabriel Paquin
     * Permet de recevoir les informations des juges.
     * @param blacklisted Information si les juges font partis de la liste noire.
     * @returns Judge[]
     */
    public static async getAllJudges(blacklisted:boolean) : Promise<APIResult<Judge[]>>{
        return await APIRequest(`judge/all/${blacklisted?"1":"0"}`,"GET",true)        
    }

    /**
     * @author Thomas-gabriel Paquin
     * Permet de mettre à jour les informations des juges
     * @param judge Judge
     * @returns Judge[]
     */
    public static async patchJudgeInfos(judge: JudgeUpdate): Promise<APIResult<Judge[]>> {
        const body = {
            judge,
        };

        const response: APIResult<Judge[]> = await APIRequest(
            "judge/update-judge",
            "PATCH",
            true,
            body
        );

        return response;
    }

    public static async changeUserRole(email:String,role:String) : Promise<string>{       
        const body = {
            email,
            role
        } 
        const response : APIResult<string> = await APIRequest(`user/change-role`,"POST",true,body)
        return response.error!;
    }
    public static async delete_user(id: number) {
        
        const response : APIResult<number> = await APIRequest(`judge/${id}`,"DELETE",true);
        return response;
    }
    /**
     * Fonnction qui permet d'appeler l'API pour avoir les rôles
     * @author Tristan Lafontaine
     * @returns {Promise<string>}
     */
    public static async getAllRoles() : Promise<APIResult<string[]>>{
        const response : APIResult<string[]> = await APIRequest(`user/all-role`,"GET",true)
        return response;
    }

    /**
     * Fonction qui permet d'appeler l'API pour ajouter un user
     * @author Maxime Demers Boucher
     * @returns {Promise<string>}
     */
    public static async addUser(body:user) : Promise<APIResult<string[]>>{
        const response : APIResult<string[]> = await APIRequest(`user/addUser`,"POST",true,body)
        return response;
    }

    /**
     * Fonction qui permet d'appeler l'API pour changer le mot de passe
     * @author Maxime Demers Boucher
     * @returns {Promise<string>}
     */
    public static async ChangePwUser(email:string,pwd:string) : Promise<APIResult<string[]>>{
        const body:changePassword = {
           email,
           pwd
        } 
        const response : APIResult<string[]> = await APIRequest(`user/change-pwd`,"PATCH",true,body)
        return response;
    }

    /**
     * Fonction qui permet d'appeler l'API pour envoier un email lors de l'oublie d'un mot de passe
     * @author Maxime Demers Boucher
     * @returns {Promise<APIResult<string[]>}
     */
    public static async PasswordForgoten(email:string,verificationCode:string) : Promise<APIResult<string[]>>{
        const body:emailMotDePasseOublier = {
            email,
            verificationCode
            } 
        const response : APIResult<string[]> = await APIRequest(`user/password-email`,"POST",true,body)
        return response;
    }
}
