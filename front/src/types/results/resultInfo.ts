/**
 * Interface pour les informations d'un résultat
 * 
 * @editeur: Francis Payan
 */
export default interface ResultInfo{
    id: number;
    categorie: string;
    survey: string;
    teams_name: string;
    first_name_user: string;
    judge_id : number;
    last_name_user: string;
    global_score: number;
    comments: string;
    person_contact: string;
    email: string;
    isChecked?: boolean;  // Indique si le score est inclus dans le calcul de la note finale
}