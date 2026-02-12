/**
 * Juge
 * @author Jean-Philippe Bourassa
 * @author Thomas-Gabriel Paquin
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
}