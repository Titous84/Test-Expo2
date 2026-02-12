import React from 'react';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';
import Button from '@mui/material/Button';
import Layout from "../../components/layout/layout";
import IPage from "../../types/IPage";
import ConnectionService from "../../api/connection/connectionService"
import { saveToken } from "../../utils/tokenUtil";
import { ShowToast } from "../../utils/utils";
import { TEXTS } from "../../lang/fr"
import styles from "./LoginPage.module.css"

/**
 * Props du composant React: LoginPage.
 * @property {string} username - Le nom d'utilisateur.
 * @property {string} password - Le mot de passe de l'utilisateur.
 * @property {boolean} connworked - Si la connexion a fonctionné ou non.
 */
interface LoginPageState{
    username: string,
    password: string,
    connworked:boolean
}

/**
 * Page de connexion
 * @author Alex Des Ruisseaux
 */
export default class LoginPage extends IPage<{}, LoginPageState> {
    constructor(props: LoginPageState){
        super(props)

        //Garde les informations
        this.state = {
            username: '',
            password: '',
            connworked:true,
        }

        this.handleSubmit = this.handleSubmit.bind(this)
    }

    //event: any changé pour React.ChangeEvent<any>
    handleChangeUserName = (event: React.ChangeEvent<any>) => {
        const un = event.target.value;
        this.setState({ username : un });
    }

    //event: any changé pour React.ChangeEvent<any>
    handleChangePWD = (event: React.ChangeEvent<any>) => {
        const pw = event.target.value;
        this.setState({ password : pw });
    }

    /**
     * Le submit du formulaire qui regarde si l'usager existe et
     *  met le token dans le localstoragae si oui et
     *  met a jour la barre de navigation en fonction de son role
     */
    async handleSubmit(){
        if (this.state.username !== '' && this.state.password !== ''){
            const response = await ConnectionService.tryGetToken(this.state.username,this.state.password)
            let token
            let role
            if (response.data){
                this.setState({connworked:true})
                token = (response).data
                if (token !== undefined){
                    saveToken(token)
                    role = await ConnectionService.tryGetUserRole()
                    
                    if (role.data){
                        window.location.href = "/"
                    }
                }
            }else{
                ShowToast(response.error!,5000,"error","top-center",false);
                this.setState({connworked:false})
            }
        }else{
            ShowToast("Email et/ou mot de passe vide",5000,"error","top-center",false);
            this.setState({connworked:false})
        }
    }
    /**
    * Vérification personnaliser
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
    //Permet d'enlever l'erreur des champs quand il respecte les critères
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse titre du stand
        ValidatorForm.removeValidationRule("maxlength");
    }

    errorMessage() {
        if (!this.state.connworked) {
          return <p className={styles.red}>{TEXTS.signin.invalide}</p>;
        }
      }

    public render() {

        /*
        *   Formulaire React
        *   https://www.npmjs.com/package/react-material-ui-form-validator
        */

        return (
            <div data-testid="pageConnexion" className={styles.toCenter}>
                <Layout name={TEXTS.signin.title}>
                    <ValidatorForm onSubmit={()=>this.handleSubmit()}>
                                    <div className={styles.padding20 + ' ' + styles.centerText}>
                                        <div>
                                            {this.errorMessage()}
                                        </div>
                                        <div className={styles.paddingForm}>
                                            <TextValidator
                                                label={TEXTS.signin.email.label}
                                                variant={"outlined"}
                                                onChange={this.handleChangeUserName}
                                                name="nomutilisateur"
                                                value={this.state.username}
                                                validators={['required','isEmail','maxlength']}
                                                errorMessages={[TEXTS.signin.email.error.required,TEXTS.signin.email.error.email,TEXTS.signin.email.error.maximum]}
                                                inputProps={{ maxLength: 255 }}
                                                className={styles.lenTextForm}
                                            />
                                        </div>
                                        <div className={styles.paddingForm}>
                                            <TextValidator
                                                label={TEXTS.signin.password.label}
                                                onChange={this.handleChangePWD}
                                                name="motdepasse"
                                                type="password"
                                                value={this.state.password}
                                                validators={['required','maxlength']}
                                                errorMessages={[TEXTS.signin.password.required,TEXTS.signin.email.error.maximum]}
                                                inputProps={{ maxLength: 255 }}
                                                className={styles.lenTextForm}
                                            />
                                        </div>
                                        <div>
                                            <Button className={styles.boutonMembre} type="submit">
                                                {TEXTS.signin.btnconn}
                                            </Button>
                                            <Button type="button" href={TEXTS.signin.href} className={styles.titre}>
                                                {TEXTS.signin.password.btnforgot}
                                            </Button>
                                        </div>
                                    </div>
                     </ValidatorForm>
                </Layout>
            </div>
        )
    }
    
}
