import { IEvaluationGridSection } from "./IEvaluationGridSection";

/*
* Interface pour une grille d'évaluation.
* Utilisé pour les modèles de grille d'évaluation
* @author Raphaël Boisvert
*/
export interface IEvaluationGrid {
    /**
     * L'id du questionnaire
     * @type {number}
        */
    id: number,

    /**
     * Le nom du questionnaire
     * @type {string}
     */
    name: string,

    /**
     * Les sections du questionnaire
     * @type {IEvaluationGridSection[]}
     */
    sections: IEvaluationGridSection[]
}