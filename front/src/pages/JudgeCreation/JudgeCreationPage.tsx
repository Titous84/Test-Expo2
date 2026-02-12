/**
 * Jean-Philippe Bourassa, basé sur le travail de Tristan Lafontaine
 */
import { Navigate } from 'react-router';
import { ValidatorForm } from 'react-material-ui-form-validator'
import { Box, Button } from '@mui/material';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import IPage from "../../types/IPage";
import Judge from '../../types/judge';
import Category from '../../types/sign-up/category';
import JudgeForm from '../../components/signupjudge/judge';
import AlertComposant from '../../components/alert/alert';
import SignUpJudgeService from '../../api/signUpJudge/signUpJudgeService';
import { EMPTY_STRING, MAX_LENGTH_EMAIL, MAX_LENGTH_FIRST_NAME, MAX_LENGTH_LAST_NAME } from '../../utils/constants';
import { ShowToast } from '../../utils/utils';
import { TEXTS } from '../../lang/fr';
import styles from "./JudgeCreationPage.module.css"

/**
 * Page d'inscription des juges
 */

interface JudgeCreationPageState {
    judge: Judge,
    error: string[],
    sendSuccess: boolean,
    categories: Category[],
    successMessage: SuccessMessage

}

interface SuccessMessage {
    firstName: string;
    lastName: string;
    email: string;
    category: string;
}

export default class JudgeCreationPage extends IPage<{}, JudgeCreationPageState> {
    constructor(props: JudgeCreationPageState) {
        super(props)

        this.state = {
            judge: {
                id: 0,
                email: "",
                firstName: "",
                lastName: "",
                category: "",
                pictureConsent: false,
                pwd: "",
                pwdconfirm: "",
                blacklisted: false,
                activated: false
            },
            error: [],
            sendSuccess: false,
            categories: [],
            successMessage: {
                firstName: "",
                lastName: "",
                email: "",
                category: ""
            }
        }


        this.handleChangeForm = this.handleChangeForm.bind(this);

    }

    /**
      * Fonction qui permet d'aller chercher les categories à l'API
      */
    async getCategory() {
        const res = await SignUpJudgeService.tryGetCategory();
        if (res.error) {
            ShowToast(res.error!, 5000, "error", "top-center", false);
        } else {
            if (res.data) {
                this.setState({ categories: res.data });
            }
        }
    }

    // Permet de modifier le state titleStand lors d'un changement dans le champs
    handleChangeForm(event: any, key: string) {
        let judge: any = { ...this.state }
        judge.judge[key] = event
        this.setState(judge)
    }

    /**
     * Vérification personnalisée
     */
    componentDidMount() {
        //Permet de récupérer les catégories lors du chargmement de la page
        this.getCategory();
        //  Vérfier la longeur du champs nom famille
        ValidatorForm.addValidationRule('maxLenghtLastName', (value) => {
            if (value.length > MAX_LENGTH_LAST_NAME) {
                return false;
            }
            return true;
        });
        //  Vérifier si le champ nom famille est vide
        ValidatorForm.addValidationRule('emptyLastName', (value) => {
            if (value === EMPTY_STRING) {
                return false;
            }
            return true;
        });
        //  Vérifier la longeur du champs prénom
        ValidatorForm.addValidationRule('maxLenghtFirstName', (value) => {
            if (value.length > MAX_LENGTH_FIRST_NAME) {
                return false;
            }
            return true;
        });
        //  Vérifier si le champ prénom est vide
        ValidatorForm.addValidationRule('emptyFirstName', (value) => {
            if (value === EMPTY_STRING) {
                return false;
            }
            return true;
        });

        //  Vérifier la longueur du champ adresse courriel
        ValidatorForm.addValidationRule('maxLenghtEmail', (value) => {
            if (value.length > MAX_LENGTH_EMAIL) {
                return false;
            }
            return true;
        });
        //  Vérifier si le champ adresse courriel est vide
        ValidatorForm.addValidationRule('emptyEmail', (value) => {
            if (value === EMPTY_STRING) {
                return false;
            }
            return true;
        });
    }

    //Permet d'enlever l'erreur des champs quand ils respectent les critères
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse courriel
        ValidatorForm.removeValidationRule('maxLenghtEmail');
        ValidatorForm.removeValidationRule('emptyEmail');
        // Retir l'erreur pour le champs prénom
        ValidatorForm.removeValidationRule('maxLenghtFirstName');
        ValidatorForm.removeValidationRule('emptyFirstName');
        // Retir l'erreur pour le champs nom famille
        ValidatorForm.removeValidationRule('maxLenghtLastName');
        ValidatorForm.removeValidationRule('emptyLastName');
    }

    /**
      * Génère les alertes lors d'erreur avec l'API.
      * @returns une alerte pour chaque erreur
      * @author Tristan Lafontaine
      * @author Étienne Nadeau
      */
    generateAlert() {
        let counter = 0;
        if (this.state.error.length > 0) {
            return this.state.error.map(error => {
                counter++;
                return <AlertComposant key={"alert" + String(counter)} typeAlert="error" errorMessage={error} titleAlert="Erreur" />
            })
        }
        else if (this.state.sendSuccess === true) {
            return <Navigate replace to="/inscription-juge-reussi" state={{ successMessage: this.state.successMessage }} />
        }
    }

    /**
      * Fonction qui permet d'envoyer le formulaire à l'API
      * @author Jean-Philippe Bourassa
      * @author Étienne Nadeau
      */
    async onSubmit() {
        const response = await SignUpJudgeService.tryPostUser(this.state.judge)
        if (response.error) {
            var error = response.error
            if (error) {
                if (error === TEXTS.api.errors.communicationFailed) {
                    this.setState({ error: Array(error) })
                } else {
                    const results: string[] = Object.values(error)
                    this.setState({ error: results })
                }
                window.scrollTo(0, 0)
            }
        } else {
            if (response.data) {
                var apiResponse = response.data
                if (apiResponse) {
                    const results: string[] = Object.values(apiResponse)
                    if (results[0] === "Le juge a été ajouté avec succès.") {
                        this.setState({ error: [] });
                        const judgeInfo = this.state.judge;
                        const successMessage = {
                            firstName: judgeInfo.firstName,
                            lastName: judgeInfo.lastName,
                            email: judgeInfo.email,
                            category: judgeInfo.category
                        };
                        this.setState({ sendSuccess: true, successMessage: successMessage })
                        window.scrollTo(0, 0)
                    }
                    else {
                        window.scrollTo(0, 0)
                        this.setState({ error: results })
                    }
                }
            }
        }
    }

    public render() {
        return (
            <Box className="centeredContainer">
                <div data-testid="inscriptionJuge" className="formContainer">
                    {this.generateAlert()}
                    <ValidatorForm
                        onSubmit={() => this.onSubmit()}
                    >
                        <h1 className={styles.title}>{TEXTS.signUpJudge.title}</h1>
                        <Box className={styles.paddingPaperTop}>
                            <div>
                                <JudgeForm judge={this.state.judge} handleChangeForm={this.handleChangeForm} categories={this.state.categories} />
                                <br />
                            </div>
                        </Box>
                        <Button variant={INPUT_VARIANT} className={styles.boutonMembre} onClick={() => this.onSubmit()}>Inscrire le juge</Button>
                    </ValidatorForm>
                    <br />
                </div>
            </Box>
        )
    }
}