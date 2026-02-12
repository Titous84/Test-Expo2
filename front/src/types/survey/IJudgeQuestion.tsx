/**
 * @author Christopher Boisvert
 *  Interface représentant une question contenu dans une section d'un formulaire d'évaluation.
 * @param id Identifiant de la question.
 * @param position Position de la question dans la section.
 * @param criteria Question à évaluer.
 * @param maxValue Valeur maximale que le juge peut attribuer. 
 * @param incrementalValue Valeur qui détermine la précision de l'évaluation entre zéro et la valeur maximale.
 */
export interface IJudgeQuestion {
    id: number,
    position:number,
    criteria:string,
    score:number,
    maxValue:number,
    incrementalValue:number
}