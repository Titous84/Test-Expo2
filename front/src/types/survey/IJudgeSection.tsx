import { IJudgeQuestion } from "./IJudgeQuestion";

/**
 * @author Christopher Boisvert
 *  Interface qui représente une section du formulaire qui contient les questions de celui-ci.
 * @param id Identifiant de la section.
 * @param position Position de la section par rapport aux autres.
 * @param name Nom de la section.
 * @param questions Tableau contenant les questions associé à la section.
 */
export interface IJudgeSection {
    id: number,
    position:number,
    name:string,
    questions:Array<IJudgeQuestion>
}