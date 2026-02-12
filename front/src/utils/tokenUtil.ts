const tokenKey = "token"
/**
 * Sauvegarde, dans le localstorage, le token.
 * @author Mathieu Sévégny
 */
export function saveToken(token:string){
    localStorage.setItem(tokenKey,token)
}
/**
 * Cherche dans le localstorage pour le token.
 * @returns Le token si présent.
 * @author Mathieu Sévégny
 */
export function getToken(): string | null{
    return localStorage.getItem(tokenKey)
}
export function removeToken(){
    localStorage.removeItem(tokenKey);
}
