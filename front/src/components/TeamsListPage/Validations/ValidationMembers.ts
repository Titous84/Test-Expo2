import { ITeamsMember } from '../../../types/TeamsList/ITeamsMember';

/**
 * Permet de valider les informations d'un membre d'équipe côté frontend.
 * @param member Le membre à valider.
 * @returns string[] Un tableau contenant les messages d'erreur.
 * 
 * @author Carlos Cordeiro
 */
export function validateMemberInfos(member: ITeamsMember): string[] {
    const errors: string[] = [];

    // Prénom
    if (!member.first_name || member.first_name.trim() === "") {
        errors.push("Le prénom ne doit pas être vide.");
    } else if (member.first_name.length > 50) {
        errors.push("Le prénom ne doit pas dépasser 50 caractères.");
    }

    // Nom
    if (!member.last_name || member.last_name.trim() === "") {
        errors.push("Le nom ne doit pas être vide.");
    } else if (member.last_name.length > 50) {
        errors.push("Le nom ne doit pas dépasser 50 caractères.");
    }

    // Consentement photo
    if (member.picture_consent !== 0 && member.picture_consent !== 1) {
        errors.push("Le consentement à la photo doit être 1 (oui) ou 0 (non).");
    }

    // Activation
    if (member.users_activated !== undefined && member.users_activated !== 0 && member.users_activated !== 1) {
        errors.push("L'activation doit être 1 (activé) ou 0 (non-activé).");
    }

    // ID Équipe
    if (!member.team_id || typeof member.team_id !== "number" || member.team_id <= 0) {
        errors.push("L'équipe sélectionnée est invalide.");
    }

    // Numéro de DA
    if (!member.numero_da || member.numero_da.trim() === "") {
        errors.push("Le numéro de DA ne doit pas être vide.");
    } else if (!/^\d+$/.test(member.numero_da)) {
        errors.push("Le numéro de DA doit contenir uniquement des chiffres.");
    }

    return errors;
}