import { IJudgeSection } from "./IJudgeSection";

/**
 * @author Christopher Boisvert
 *  Interface représentant un formulaire d'évaluation associé à une équipe.
 * @param id Identifiant du formulaire.
 * @param stand_name Nom de l'équipe.
 * @param stand_id Identifiant de l'équipe.
 * @param sections Sections du formulaires contenant les différentes questions.
 */
export interface IEvaluation {
    id: number,
    stand_name:string,
    stand_id:number,
    evaluation_start:string,
    score:number,
    sections:Array<IJudgeSection>,
    comments:string,
}