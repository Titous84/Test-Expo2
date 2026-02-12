/**
 * Juge avec les informations qui peuvent être modifiées
 * @author Thoams-Gabriel Paquin
 */
 export default interface JudgeUpdate{
      /**
      * Identifiant du juge
      */
      id:number;
    /**
     * Prénom du juge
     */
     firstName:string;
     /**
      * Nom de famille du juge
      */
     lastName:string;
     /**
      * Adresse courriel du juge
      */
     email:string;
     /**
     * Catégorie
     */
    categoryId:number;
     /**
      * Blacklist
      */
     blacklisted:boolean;
     /**
      * Activé
      */
     activated:boolean;
}