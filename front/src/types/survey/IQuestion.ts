
/**
 * Interface pour les questions
 * @author Tomy Chouinard
 */
export default interface IQuestion{
    id?: number,
    rating_section_id:number,
    criteria:string,
    position:number,
    max_value:number,
    incremental_value:number
}