import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import {v4 as uuidv4} from 'uuid';

export type MessageTypes = "success" | "error" | "warning" | "info" | "default";
export type MessagePosition = "top-left" | "top-center" | "top-right" | "bottom-left" | "bottom-center" | "bottom-right";

/**
 * Crée une clé unique.
 * @example
 * list.map(item => {
 *     return <h1 key={createRandomKey()}></h1>
 * })
 * @returns Clé unique
 */
export function createRandomKey(){
    return uuidv4();
}

/**
 * Vérification de l'égalité entre deux tableaux de tableaux.
 * @param a Premier tableau de tableaux à tester.
 * @param b Deuxième tableau de tableaux à tester.
 * @returns Vrai -> Égaux, Faux -> Inégaux
 */
export function AOATester(a:any[][],b:any[][]){
    for (let i = 0; i < a.length; i++) {
        for (let j = 0; j < a[i].length; j++) {
            if (a[i][j] !== b[i][j]){
                return false;
            }
        }
    }
    return true;
}

export function suffix(suffix: number): string{
    if(suffix === 1){
        return "er"
    }
    return "e"
}

/**
 * Affiche un petit pop-up dans la page
 * @param message message à afficher dans le pop-up
 * @param delay délai que le pop-up doit être affiché
 * @param type le type du pop-up (success, error, info, warning, default)
 * @param position la position du pop-up à l'écran
 * @param hideProgressBar la barre doit être afficher ou pas? 
 * @returns le pop-up
 */
export function ShowToast(message:string,delay:number,type:MessageTypes, position:MessagePosition = "top-center", hideProgressBar:boolean){
    return toast(message,{
        type:type,
        theme:"colored",
        position: position,
        autoClose: delay,
        hideProgressBar: hideProgressBar,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
    })
}
/**
 * Aide l'ordonnance d'éléments par un nombre.
 * @author Mathieu Sévégny
 * @param a Premier élément
 * @param b Second élément
 */
export function sortByNumber(a:number,b:number){
    if (a < b) return -1;
    if (a > b) return 1;
    else return 0;
}
/**
 * Vérifie la présence d'un élément dans une liste d'objets.
 * @author Mathieu Sévégny
 * @param element Élément à trouver
 * @param array Tableau dans lequel chercher
 * @param field Champ à comparer
 * @returns Vrai si présent, faux si absent.
 */
export function isInArray(element:any,array:any[],field:string){
    for (let i = 0; i < array.length; i++) {
        const item = array[i];
        if (item[field] === element[field]){
            return true;
        }
    }
    return false;
}
