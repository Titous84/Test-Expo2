import { ITeamsMember } from './ITeamsMember';

/**
 * Interface représantant une équipe
 * 
 * @interface ITeam
 * @author Carlos Cordeiro
 */
export interface ITeam {

    /**
     * L'id de l'équipe
     */
    team_id: number,

    /**
     * Le numéro de l'équipe
     */
    team_number: string,

    /**
     * Le titre de l'équipe
     */
    title: string,

    /**
     * La description de l'équipe
     */
    description: string,

    /**
     * L'année de l'équipe
     */
    year: string,

    /**
     * La catégorie de l'équipe
     */
    category: string,

    /**
     * Le type d'évaluation de l'équipe
     */
    survey: string,

    /**
     * L'activation de l'équipe
     */
    teams_activated: number,

    /**
     * Le nom de l'enseignant(e) de l'équipe
     */
    contact_person_name: string,

    /**
     * L'adresse courriel de l'enseignant(e) de l'équipe
     */
    contact_person_email: string,

    /**
     * Les membres de l'équipe
     */
    members: ITeamsMember[],

    /**
     * Typage explicite pour différencier les équipes
     */
    type: string,
}