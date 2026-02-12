import { IEvaluationGridCriteria } from "./IEvaluationGridCriteria";

/*
* Interface pour les sections d'une grille d'évaluation.
* Utilisé pour les modèles de grille d'évaluation
* @author Raphaël Boisvert
*/
export interface IEvaluationGridSection {
    /**
     * L'id de la section
     * @type {number}
     */
    id: number,

    /**
     * Le nom de la section
     * @type {string}
     */
    name: string,

    /**
     * Position de la section
     * @type {number}
     */
    position: number,

    /**
     * Les critères de la section
     * @type {IEvaluationGridCriteria[]}
     */
    criterias: IEvaluationGridCriteria[]
}