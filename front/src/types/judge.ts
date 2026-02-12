/**
 * Juge
 * @author Jean-Philippe Bourassa
 * @author Thomas-Gabriel Paquin
 * @author Nathan Reyes
 */
 export default interface Judge{
      /**
      * Identifiant du juge
      */
      id:number;
    /**
     * Prénom du juge (max 40 char.)
     */
     firstName:string;
     /**
      * Nom de famille du juge (max 40 char.)
      */
     lastName:string;
     /**
      * Adresse courriel du juge (max 255 char.)
      */
     email:string;
     /**
     * Catégorie (sélection dans une liste de choix)
     */
    category:string;
     /**
      * Consentement d'être pris en photo
      */
     pictureConsent:boolean;
     /**
      * Mot de passe du juge
      */
     pwd:string;
     /**
      * Mot de passe du juge
      */
     pwdconfirm:string;
     /**
      * Blacklist
      */
     blacklisted:boolean;
     /**
      * Activé
      */
     activated:boolean;

    /**
     * Indique si le juge participe réellement à l'édition courante.
     */
    isPresentCurrentEdition?:boolean;

    /**
     * Indique si au moins une équipe est attribuée au juge.
     */
    hasAssignedTeam?:boolean;
}
