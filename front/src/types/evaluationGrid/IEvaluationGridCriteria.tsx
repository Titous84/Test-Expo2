/*
* Interface pour les critères d'une section d'une grille d'évaluation.
* Utilisé pour les modèles de grille d'évaluation
* @author Raphaël Boisvert
*/

export interface IEvaluationGridCriteria {
    /**
     * L'id du critère
     * @type {number}
     */
    id: number,

    /**
     * Le nom du critère
     * @type {string}
     */
    name: string,

    /**
     * La position du critère
     * @type {number}
     */
    position: number,

    /**
     * La valeur maximale du critère
     * @type {number}
     */
    max_value: number,

    /**
     * La valeur incrémentale du critère
     * @type {number}
     */
    incremental_value: number
}