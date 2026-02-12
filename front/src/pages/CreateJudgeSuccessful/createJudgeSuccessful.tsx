import styles from "./createJudgeSuccessful.module.css"
import MarkEmailReadIcon from '@mui/icons-material/MarkEmailRead';
import { Link, useLocation } from "react-router-dom";
import { Button } from "@mui/material";
import { INPUT_VARIANT } from "../../utils/muiConstants";
import { TEXTS } from "../../lang/fr";

// Interface pour le message de succès
interface SuccessMessage {
    firstName: string;
    lastName: string;
    email: string;
    category: string;
}

/**
 * Page de validation d'inscription d'un juge
 * //Inspirer de Tristan Lafontaine et du ficher CreateTeamSuccessful.tsx
 * @author Étienne Nadeau
 * @description Cette page retourne un message de succès après l'inscription d'un juge (avec les détails du juge)
 * @returns {JSX.Element} La page de validation d'inscription d'un juge
 */
const CreateJudgeSuccessful = () => {
    const location = useLocation();
    const { successMessage } = location.state as { successMessage: SuccessMessage } || { successMessage: { firstName: "", lastName: "", email: "", category: "" } };

    return (
        <div data-testid="teamCreation" className={`${styles.centered} ${styles.pageFullScreen}`}>
            <MarkEmailReadIcon className={styles.icon} />
            <h1 className={styles.titre}>{TEXTS.signUpJudgeSuccess.title}</h1>
            <div className={styles.successMessage}>
                <p>{TEXTS.signUpJudgeSuccess.content}</p>
                <div className={styles.judgeInfo}>
                    <p>{TEXTS.signUpJudgeSuccess.judgeName} {successMessage.firstName +" "+ successMessage.lastName} </p>
                    <p>{TEXTS.signUpJudgeSuccess.judgeEmail} {successMessage.email}</p>
                    <p>{TEXTS.signUpJudgeSuccess.judgeCategory} {successMessage.category}</p>
                </div>
            </div>
            <Link to={"/"}>
                <Button variant={INPUT_VARIANT} className={styles.bouton} color="primary">{TEXTS.generic.goToHome}</Button>
            </Link>
        </div>
    );
};

export default CreateJudgeSuccessful;