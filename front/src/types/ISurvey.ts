
// Interface pour le type de formulaire et d'équipe ex: SAT, DD ...
export interface ISurvey{
    id:number
    name:string;
}

export class Survey{
    public static Survey:ISurvey[] = []
}