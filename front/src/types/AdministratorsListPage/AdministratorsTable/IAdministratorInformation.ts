/**
 * Informations affichées pour chaque administrateur dans le tableau des administrateurs.
 * Correspond donc à une rangée du tableau.
 * 
 * (Les administrateurs sont ceux qui ont accès à la page d'administration.)
 * 
 * @property {number} id - L'identifiant unique de l'administrateur dans la base de données.
 *                         L'id n'est pas affiché, mais il est nécessaire pour la modification et la suppression.
 * @property {string} email - L'adresse courriel de l'administrateur pour se connecter.
 * @property {boolean} isActive - Indique si l'administrateur est actif ou non.
 *                                Lorsqu'un administrateur est désactivé, il ne peut plus
 *                                se connecter au site web.
 * 
 * Note: ne pas envoyer le hash du mot de passe au Front-end. C'est le Back-end qui vérifie les mots de passe.
 * @author Antoine Ouellette
 */
export default interface IAdministratorInformation {
    id: number;
    email: string;
    isActive: boolean;
}