/**
 * Interface représentant un membre d'une équipe
 * 
 * @interface ITeamsMember
 * @author Carlos Cordeiro
 */
export interface ITeamsMember {

    /**
     * L'ID du membre
     */
    id: number,

    /**
     * Le numéro de DA du membre
     */
    numero_da:string,

    /**
     * Le prénom du membre
     */
    first_name:string,

    /**
     * Le nom de famille du membre
     */
    last_name:string,

    /**
     * Le status de liste noire du membre
     */
    blacklisted: number,

    /**
     * Le status de consentement à la photo du membre
     */
    picture_consent: number,

    /**
     * Le status d'activation du membre
     */
    users_activated:number,

    /**
     * L'ID de l'équipe du membre
     */
    team_id: number,

    /**
     * Typage explicite pour différencier les membres
     */
    type: "member"
}