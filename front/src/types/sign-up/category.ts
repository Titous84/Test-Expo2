/**
 * Nom de la catégorie
 */
export default interface Category{
    /**
    * L'id de la catégorie
    */
    id: number;
    
    /**
    * Nom de la catégorie
    */
    name:string;

    /**
    * Le nombre maximum de membre par catégorie
    */
    max_members:number;

    /**
    * Acronyme de la catégorie
    */
    acronym?:string;
}