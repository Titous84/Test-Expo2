import React from 'react';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';
import Button from '@mui/material/Button';
import Layout from "../../components/layout/layout";
import IPage from "../../types/IPage";
import UserService from "../../api/users/userService";
import VerificationCodeService from "../../api/verificationCode/verificationCodeService";
import { ShowToast } from "../../utils/utils";
import { TEXTS } from "../../lang/fr";
import styles from "./ForgottenPasswordPage.module.css"

/**
 * Variables d'états du composant React: ForgottenPasswordPage.
 * @property {string} email - Adresse courriel de l'utilisateur.
 */
interface ForgottenPasswordPageState{
    email: string,
}

/**
 * Page mot de passe oublié
 * @author Alex Des Ruisseaux
 */
export default class ForgottenPasswordPage extends IPage<{}, ForgottenPasswordPageState> {
    constructor(props: {}) {
        super(props);

        // Initialisation des variables d'états.
        this.state = {
            email: '',
        }
    }

    /**
     * Méthode appelée lorsque la valeur du champ du courriel change.
     * Change la valeur de la variable d'état.
     */
    handleChangeEmail = (event: React.ChangeEvent<any>) => {
        const change = event.target.value;
        this.setState({ email : change });       
    }

    /**
     * Méthode appelée lors de la soumission du formulaire.
     * Envoie une requête pour générer un code de vérification et l'envoie à l'adresse courriel de l'utilisateur.
     */
    async handleSubmit(){
        const responsecode = await VerificationCodeService.generateVerificationCode(this.state.email);
        const code = responsecode.data;
        const response = await UserService.PasswordForgoten(this.state.email,String(code));
        if (response.data) {
            ShowToast("Un email à été envoyé pour changer votre mot de passe",5000,"success","top-center",false);
        } else {
            ShowToast("L'email n'a pa été envoyé",5000,"error","top-center",false);
        }
    }

    /**
     * Vérification personnaliser
     */
    componentDidMount() {
        // Vérifie la longeur du champs Titre du projet
        ValidatorForm.addValidationRule('maxlength', (value) => {
            if (value.length > 255 || value.length == null) {
                return false;
            }

            return true;
        });
    }

    /**
     * Permet d'enlever les messages d'erreurs des champs quand ils respectent les critères.
     */
    componentWillUnmount() {
        ValidatorForm.removeValidationRule('maxlength');
    }

    public render() {
        /**
         * @see https://www.npmjs.com/package/react-material-ui-form-validator
         */
        return (
            <div className={styles.toCenter}>
                <Layout name={TEXTS.signin.password.title}>
                    <ValidatorForm
                        onSubmit={()=>this.handleSubmit()}
                        onError={errors => ()=>ShowToast(errors.toString(),5000,"error","top-center",false)}
                        className={styles.padding}
                    >
                        <div className={styles.paddingSquare + ' ' + styles.centerText}>
                            <TextValidator
                                label={TEXTS.signin.email.label}
                                onChange={this.handleChangeEmail}
                                name="Adresse courriel"
                                validators={['required','isEmail','maxlength']}
                                errorMessages={[TEXTS.signin.email.error.required,TEXTS.signin.email.error.email,TEXTS.signin.email.error.maximum]}
                                value={this.state.email}
                                className={styles.paddingForm}
                            />
                        </div>
                        <div data-testid="motDePasseOublier" className={styles.paddingSquare + ' ' + styles.centerText}>
                            <Button type="submit" className={styles.btnConnexion+ ' ' + styles.btnHover}>{TEXTS.signin.btnemail}</Button>
                        </div>
                    </ValidatorForm>
                </Layout>              
            </div>
        )
    }
}