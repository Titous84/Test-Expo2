import ContactPerson from "./contact-person";
import TeamMember from "./team-member";

/**
 * Interface pour la page d'inscription.
 * Mathieu Sévégny
 */
export default interface TeamInfo{
    /**
     * Titre du projet (max 30 char.)
     */
    title:string;
    /**
     * Description du projet (max 250 char.)
     */
    description:string;
    /**
     * Catégorie (sélection dans une liste de choix)
     */
    category:string;
    /**
     * Année d'étude
     */
    year:"1re année" | "2e année et +";
    /**
     * Personne-ressource
     */
    contactPerson:ContactPerson[];
    /**
     * Membres de l'équipe (min 2, max 8)
     */
    members:TeamMember[];
    /**
     * Type d'équipe (ex: "team" ou "member")
     */
    type?: string;
}