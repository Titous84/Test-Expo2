import { TEXTS } from "../lang/fr";
import APIResult from "../types/apiResult";
import { getToken } from "../utils/tokenUtil";

/**
 * Base de l'URL de l'API.
 */
if (!import.meta.env.VITE_BASE_URL || !import.meta.env.VITE_API_URL) 
    throw new Error("Missing environment variable: VITE_BASE_URL and/or VITE_API_URL");

export const API_BASE_URL = import.meta.env.VITE_BASE_URL + import.meta.env.VITE_API_URL;

/**
 * Méthode HTTP.
 */
type Method = "GET" | "POST" | "PATCH" | "PUT" | "DELETE"

/**
 * Crée les options pour une requête Fetch.
 * @param method Méthode HTTP voulue
 * @param isAuth Est-ce que l'usager est connecté?
 * @param data Données à envoyer dans le corps de la requête. (Si besoin)
 * @returns « Init » servant à faire une requête Fetch.
 * @author Mathieu Sévégny
 * @example
 * await fetch(url+ "/login",createRequestOptions("POST",false,body))
         .then(async (response) => {
                user = JSON.parse(await response.text());
              })
 */
export function createRequestOptions(method:Method,isAuth:boolean,data?:any){
    //Crée un header pour la requête
    let header = new Headers();
    //Spécifier que le contenu est du json
    header.append("Content-Type","application/json")

    let init : any = {method:method};
    if (isAuth) {
        let letoken = getToken();
        if(letoken != null){
            header.append("Authorization",`Bearer ${letoken}`)
        }
       
    }
    //Ajouter le header dans le init
    init.headers = header;

    if (data !== null){
        //Ajoute l'objet en string dans le body du init
        init.body = JSON.stringify(data);
    }

    return init;
}
/**
 * Réalise une requête à l'API.
 * @author Mathieu Sévégny
 * @param endURL Fin de l'URL
 * @param method Méthode HTTP voulue
 * @param isAuth Est-ce que l'usager est connecté?
 * @param body Données à envoyer dans le corps de la requête. (Si besoin)
 * @returns La réponse de l'API
 */
export async function APIRequest<T>(endURL: string, method: Method, isAuth: boolean, body?: any): Promise<APIResult<T>> {
    let response;
    let data;
    let error;
    try {
        const res = await fetch(createAPIURL(endURL), createRequestOptions(method, isAuth, body));
        const text = await res.text();

        try {
            response = JSON.parse(text);
        } catch (e) {
            error = TEXTS.api.errors.communicationFailed;
            return { data, error };
        }

        if (!res.ok) {
            // Si le champ « message » est vide, donne le message d'erreur de l'API
            error = response.message || TEXTS.api.errors.communicationFailed;
        } else {
            // Si le champ « content » est vide, donne le message d'erreur de l'API
            if (!response.content) {
                error = response.message;
            } else {
                data = response.content;
            }
        }
    } catch (e) {
        error = TEXTS.api.errors.communicationFailed;
    }

    return { data, error };
}
/**
 * Crée un lien vers l'API.
 */
export function createAPIURL(endURL:string){
    return API_BASE_URL+endURL;
}
