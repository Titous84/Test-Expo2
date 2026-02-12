import { Link, useLocation } from "react-router-dom";
import { Button } from "@mui/material";
import MarkEmailReadIcon from '@mui/icons-material/MarkEmailRead';
import { INPUT_VARIANT } from "../../utils/muiConstants";
import { TEXTS } from "../../lang/fr";
import styles from "./TeamCreationSuccessPage.module.css"

/**
 * Interface pour les membres de l'équipe
 * @property {string} firstName - Le prénom du membre
 * @property {string} lastName - Le nom de famille du membre
 * @property {string} numero_da - Le numéro d'étudiant du membre
 */
interface Member {
    firstName: string;
    lastName: string;
    numero_da: string;
}

/**
 * Interface pour le message de succès
 * @property {string} title - Le titre du message de succès
 * @property {string} description - La description du message de succès
 * @property {string} category - La catégorie de l'équipe. Ex: "SH - Intervention sociale"
 * @property {string} year - L'année d'étude de l'équipe
 * @property {Member[]} members - La liste des membres de l'équipe
 */
interface SuccessMessage {
    title: string;
    description: string;
    category: string;
    year: string;
    members: Member[];
}

/**
 * Page du message de succès affiché après l'inscription d'une équipe.
 * @author Tristan Lafontaine
 * 
 * @description Cette page retourne un message de succès après l'inscription d'une équipe (avec les détails de l'équipe inscrite)
 * @returns {JSX.Element} La page de validation d'inscription d'une équipe
 */
const TeamCreationSuccessPage = () => {
    const location = useLocation();
    const { successMessage } = location.state as { successMessage: SuccessMessage } || { successMessage: { title: "", description: "", category: "", year: "", members: [] } };

    return (
        <div data-testid="teamCreation" className={`${styles.centered} ${styles.pageFullScreen}`}>
            <MarkEmailReadIcon className={styles.icon} />
            <h1 className={styles.titre}>{TEXTS.signUpSuccess.title}</h1>
            <div className={styles.successMessage}>
                <p>{TEXTS.signUpSuccess.content}</p>
                <div className={styles.teamInfo}>
                    <p>{TEXTS.signUpSuccess.teamTitle} {successMessage.title}</p>
                    <p>{TEXTS.signUpSuccess.teamDescription} {successMessage.description}</p>
                    <p>{TEXTS.signUpSuccess.teamCategory} {successMessage.category}</p>
                    <p>{TEXTS.signUpSuccess.teamYear} {successMessage.year}</p>
                    <p>{TEXTS.signUpSuccess.teamMembers}</p>
                    {Array.isArray(successMessage.members) && successMessage.members.map((member: Member, index: number) => (
                        <div key={index} className={styles.member}>
                            <p>{TEXTS.signUpSuccess.memberName} {member.firstName} {member.lastName}</p>
                            <p>{TEXTS.signUpSuccess.memberNumeroDA} {member.numero_da}</p>
                        </div>
                    ))}
                </div>
            </div>
            <Link to={"/"}>
                <Button variant={INPUT_VARIANT} className={styles.bouton} color="primary">{TEXTS.generic.goToHome}</Button>
            </Link>
        </div>
    );
};

export default TeamCreationSuccessPage;