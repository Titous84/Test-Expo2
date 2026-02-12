import UserService from '../api/users/userService';
import { RoleName as RolePath } from '../router/routes';
import { getToken } from './tokenUtil';

/**
 * @author Charles Lavoie
 * Classe pour garder en mémoire le rôle actuel
 */
export class ActualRole {
    //N'a aucun rôle au départ
    static actual : RolePath | null = null;
    static promise : Promise<RolePath> | null = null

    static async get() : Promise<RolePath>{
        if (this.actual) return this.actual;
        if (!getToken()) return "Guest";
        if (!this.promise){
            this.promise = getRole()
        }

        this.actual = await this.promise;
        
        return this.actual;
    }
    /**
     * Changer un String en Role pour la classe
     * @param string String à modifier en RolePath
     * @returns String de départ en RolePath
     */
    toRole(string : String) {
        const newRole : RolePath = string as RolePath;
        return newRole;
    }
}
/**
 * Utiliser le token de localStorage pour retrouver le rôle
 * de l'utilisateur sans sauvegarder dans localStorage pour
 * des problèmes de sécurité.
 * @returns Le rôle actuel pour l'utilisateur de l'api
 * @author Charles Lavoie
 */
export async function getRole() : Promise<RolePath>{
    const token = getToken();
    const response : String = await UserService.tryGetRole(token!);
    const role : RolePath = toRole(response);
    return role;
}
/**
 * Retourner un String en RolePath, ne s'occupe pas
 * de la vérification d'un bon rôle. Utilisable ailleurs
 * que dans la classe.
 * @param string Le String à transférer en RolePath
 * @returns Le RolePath ayant le nom du String
 * @author Charles Lavoie
 */
export function toRole(string:String) : RolePath{
    const newRole : RolePath = string as RolePath;
    return newRole;
}