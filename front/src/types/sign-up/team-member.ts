/**
 * Membre d'équipe.
 * Mathieu Sévégny
 */
export default interface TeamMember{
    /**
     * Prénom du membre (max 40 char.)
     */
    firstName:string;
    /**
     * Nom de famille du membre (max 40 char.)
     */
    lastName:string;
    /**
     * Numéro de DA du membre (max 255 char.)
     */
    numero_da:string;
    /**
     * Clause de consentement photo: 0 = refus total, 1 = publication externe, 2 = usage interne seulement
     */
    pictureConsent:number;
    /**
     * Masquer le prénom du membre dans les listes publiques
     */
    hideFirstName:boolean;
    /**
     * Masquer le nom du membre dans les listes publiques
     */
    hideLastName:boolean;
    /**
     * Masquer le numéro DA du membre dans les listes publiques
     */
    hideNumeroDa:boolean;
}