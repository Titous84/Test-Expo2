import React from 'react';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';
import Button from '@mui/material/Button';
import IPage from "../../types/IPage";
import Layout from "../../components/layout/layout";
import UserService from "../../api/users/userService";
import verificationCodeService from "../../api/verificationCode/verificationCodeService";
import { ShowToast } from '../../utils/utils';
import { TEXTS } from "../../lang/fr";
import styles from "./ForgottenPasswordModificationPage.module.css";

/**
 * Props du composant React: ForgottenPasswordModificationPage.
 */
interface ForgottenPasswordModificationPageState {
    codeVerification: string,
    newPassword: string,
    verifyPassword: string
}

/**
 * Page pour changer le mot de passe oublié.
 * @author Maxime Demers Boucher
 */
export default class ForgottenPasswordModificationPage extends IPage<{}, ForgottenPasswordModificationPageState> {
    constructor(props:ForgottenPasswordModificationPageState) {
        super(props)

        // Variables d'état
        this.state = {
            codeVerification:'',
            newPassword:'',
            verifyPassword:'',
        }
    }

    /**
     * Change la valeur de newPassword
     * @param event 
     */
    handleChangeOnNewPassword = (event: React.ChangeEvent<any>) => {
        const np = event.target.value;
        this.setState({ newPassword : np });
    }

    /**
     * Change la valeur de verifyPassword
     * @param event 
     */
    handleChangeOnVerifyPassword = (event: React.ChangeEvent<any>) => {
        const vp = event.target.value;
        this.setState({ verifyPassword : vp });
    }

    /**
     * Change la valeur de codeVerification
     * @param event 
     */
    handleChangeOnCodeVerification = (event: React.ChangeEvent<any>) => {
        const codeVerification = event.target.value;
        this.setState({ codeVerification : codeVerification });
    }

    /**
     * Vérification personnalisée
     */
    componentDidMount() {
        //  Vérfier la longeur du champs Titre du stand
        ValidatorForm.addValidationRule('maxlength', (value) => {
            if (value.length > 255) {
                return false;
            }
                return true;
        });
    }

    /**
     * Permet d'enlever l'erreur des champs quand il respecte les critères
     */
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse titre du stand
        ValidatorForm.removeValidationRule('maxlength');
    }
      
    /**
     * Le submit du formulaire d'ajout d'un admin
     */
    async handleSubmit(){
        if (this.state.newPassword !== '' && this.state.verifyPassword !== '' && this.state.codeVerification !==''){
            const codeValide = await verificationCodeService.validateVerificationCode(this.state.codeVerification);
            console.log(codeValide);
            if(codeValide.data){
                if(this.state.newPassword === this.state.verifyPassword){
                    //const id = await UserService.getId
                    const email = codeValide.data.email;
                    const response = await UserService.ChangePwUser(email,this.state.newPassword);
                    if (response.data){
                        const deleted = await verificationCodeService.deleteVerificationCode(email);
                        if(deleted.data){
                        //retour dans la page de connection
                        window.location.href = "/connexion";
                        }
                        else{
                            ShowToast("Le code de vérification n'a pas été suprimé après le changement de mot de passe",5000,"error","top-center",false);
                        }
                    }else{
                        ShowToast("Le courriel n'est pas associé à un compte administrateur",5000,"error","top-center",false);
                    }
                }
                else{
                    ShowToast("Le mot de passe doit être le même dans les deux champs",5000,"error","top-center",false);
                }
            }
            else{
                ShowToast("le code est invalide ou il est expiré",5000,"error","top-center",false);
            }
        }else{
            ShowToast("L'un des champs est vide",5000,"error","top-center",false);
        }
    }

    public render() {
        /**
         * @see https://www.npmjs.com/package/react-material-ui-form-validator
         */
        return (
            <div data-testid="ChangePWF" className={styles.toCenter}>
                <Layout name={TEXTS.modifyPassword.title}>
                    <ValidatorForm onSubmit={()=>this.handleSubmit()}>
                                    <div className={styles.padding20 + ' ' + styles.centerText}>
                                        <div className={styles.paddingForm}>
                                            <TextValidator
                                                label="Code de vérification"
                                                variant={"outlined"}
                                                onChange={this.handleChangeOnCodeVerification}
                                                name="Code de vérification"
                                                value={this.state.codeVerification}
                                                validators={['required','maxlength']}
                                                errorMessages={["Le code de vérification est requie","la longueur du code vérifcation est 12 caractère"]}
                                                inputProps={{ maxLength: 12 }}
                                                className={styles.lenTextForm}
                                            />
                                        </div>
                                        <div className={styles.paddingForm}>
                                            <TextValidator
                                                label={TEXTS.modifyPassword.newPassword.label}
                                                variant={"outlined"}
                                                onChange={this.handleChangeOnNewPassword}
                                                name="newPassword"
                                                value={this.state.newPassword}
                                                validators={['required','maxlength']}
                                                errorMessages={[TEXTS.modifyPassword.newPassword.error.required,TEXTS.modifyPassword.newPassword.error.maximum]}
                                                inputProps={{ maxLength: 50 }}
                                                className={styles.lenTextForm}
                                            />
                                        </div>
                                        <div className={styles.paddingForm}>
                                            <TextValidator
                                                label={TEXTS.modifyPassword.verifyPassword.label}
                                                variant={"outlined"}
                                                onChange={this.handleChangeOnVerifyPassword}
                                                name="verifyPassword"
                                                value={this.state.verifyPassword}
                                                validators={['required','maxlength']}
                                                errorMessages={[TEXTS.modifyPassword.verifyPassword.error.required,TEXTS.modifyPassword.verifyPassword.error.maximum]}
                                                inputProps={{ maxLength: 50 }}
                                                className={styles.lenTextForm}
                                            />
                                        </div>
                                        <div>
                                            <Button className={styles.boutonMembre} type="submit">
                                                {TEXTS.ajoutAdmin.btnconn}
                                            </Button>
                                        </div>
                                    </div>
                     </ValidatorForm>
                </Layout>
            </div>
        )
    }
}