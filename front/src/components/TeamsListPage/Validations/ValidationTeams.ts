import { ITeam } from '../../../types/TeamsList/ITeam';

/**
 * Permet de valider les informations d'une équipe côté frontend.
 * @param team L'équipe à valider.
 * @returns string[] Un tableau contenant les messages d'erreur.
 * 
 * @author Carlos Cordeiro
 */
export function validateTeamInfos(team: ITeam): string[] {
    const errors: string[] = [];

    // Titre (du projet) de l'équipe
    if (!team.title || team.title.trim() === "") {
        errors.push("Le titre du projet est obligatoire.");
    } else if (team.title.length > 30) {
        errors.push("Le titre du projet ne doit pas dépasser 30 caractères.");
    }

    // Description (du projet) de l'équipe
    if (!team.description || team.description.trim() === "") {
        errors.push("La description du projet est obligatoire.");
    } else if (team.description.length > 250) {
        errors.push("La description du projet ne doit pas dépasser 250 caractères.");
    }

    // Catégorie (du projet) de l'équipe
    if (!team.category || team.category.trim() === "") {
        errors.push("La catégorie est obligatoire.");
    }

    // Année (du projet) de l'équipe
    if (!team.year || team.year.trim() === "") {
        errors.push("L'année est obligatoire.");
    }

    // Nom de la personne ressource
    if (!team.contact_person_name || team.contact_person_name.trim() === "") {
        errors.push("Le nom de l'enseignant(e) est obligatoire.");
    }

    // Adresse courriel de la personne ressource
    if (!team.contact_person_email || team.contact_person_email.trim() === "") {
        errors.push("L'adresse courriel de l'enseignant(e) est obligatoire.");
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(team.contact_person_email)) {
        errors.push("L'adresse courriel de l'enseignant(e) est invalide.");
    }

    return errors;
}