import { IEvaluation } from "../survey/IEvaluation";

/**
 * Interface représentant les différents états lors de la recherche d'un formulaire d'évaluation.
 * @author Christopher Boisvert
 * 
 * @param pageName Nom de la page.
 * @param isSearchingEvaluation Valeur booléenne qui indique la page cherche toujours un formulaire.
 * @param isEvaluationFound Valeur booléenne qui indique si un formulaire a été trouvé.
 * @param isEvaluationCompleted Valeur booléenne qui indique si tous les formulaires ont été remplis.
 * @param evaluationsList Tableau contenant les différents formulaires d'évaluations.
 */
export interface IJudgeEvaluation {
    pageName:string,
    isSearchingEvaluation:boolean,
    isEvaluationFound:boolean,
    isEvaluationCompleted:boolean,
    evaluationsList: IEvaluation[]
}