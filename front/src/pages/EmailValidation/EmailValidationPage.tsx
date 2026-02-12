import React from "react";
import { useParams } from "react-router";
import { Link } from "react-router-dom";
import { Button } from "@mui/material";
import MarkEmailReadIcon from '@mui/icons-material/MarkEmailRead';
import UserService from "../../api/users/userService";
import { INPUT_VARIANT } from "../../utils/muiConstants";
import { TEXTS } from "../../lang/fr";
import styles from "./EmailValidationPage.module.css"

/**
 * Page de validation d'une adresse courriel.
 */
export function EmailValidationPage(){
    // Récupération du token dans l'URL.
    const {token} = useParams()

    return(
        <EmailValidationPageContent token={token}/>
    )
}

/**
 * Props du composant React: EmailValidationPageContent.
 * @property {string | undefined} token - Le token de validation d'adresse courriel.
 */
interface EmailValidationPageContentProps{
    token: string | undefined;
}

/**
 * Variables d'état du composant React: EmailValidationPageContent.
 * @property {string | null} response - La réponse du serveur concernant la validation d'adresse courriel.
 */
interface EmailValidationPageContentState{
    response: string | null;
}

/**
 * Page de validation d'adresse courriel
 * @author Mathieu Sévégny
 */
export class EmailValidationPageContent extends React.Component<EmailValidationPageContentProps,EmailValidationPageContentState> {
    constructor(props: EmailValidationPageContentProps){
        super(props)

        // Variables d'état
        this.state = {
            response: null
        }

        this.activate();
    }
    
    /**
     * Méthode qui valide l'adresse courriel de l'utilisateur et enregistre le résultat dans le state.
     */
    async activate(){
        if (!this.props.token) return;

        const response = await UserService.tryValidateEmail(this.props.token)
        this.setState({ response: response })
    }

    public render() {
        return (
            <div data-testid="emailValidation" className={styles.centered}>
                <MarkEmailReadIcon className={styles.icon}/>
                <h1 className={styles.titre}>{this.state.response}</h1>
                <Link to={"/"}>
                    <Button variant={INPUT_VARIANT} className={styles.bouton} color="primary">{TEXTS.generic.goToHome}</Button>
                </Link>
            </div>
        )
    }
}