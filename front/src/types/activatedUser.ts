/**
 * Interface représentant l'usager et ses informations.
 */
 export default interface ActivatedUser {
    // un id unique nécessaire pour afficher dans le tableau
    id:number,
    // prenom de l'usager
    firstName:string,
    //nom de famille de l'usager
    lastName:string,
    //adresse courriel de l'usager
    email:string,
    //nom du role mis sur l'usager
    name:string
}