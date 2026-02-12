import React from "react";
import { useParams } from "react-router";
import { Button } from "@mui/material";
import { INPUT_VARIANT } from "../../utils/muiConstants";
import { TEXTS } from "../../lang/fr";
import JudgeVerificationForm from "../../components/verificationjudge/verificationjudge";
import Judge from "../../types/judge";
import Category from '../../types/sign-up/category';
import Box from '@mui/material/Box';
import { ValidatorForm } from 'react-material-ui-form-validator'
import SignUpJudgeService from "../../api/signUpJudge/signUpJudgeService";
import { ShowToast } from "../../utils/utils";
import styles from "./EmailValidationJudgePage.module.css"

/**
 * Page de validation de l'adresse courriel d'un juge.
 * @author Jean-Philippe Bourassa
 */
export function EmailValidationJudgePage(){
    // Récupère le token dans l'URL.
    const {token} = useParams()

    return(
        <EmailValidationJudgePageContent token={token}/>
    )
}

/**
 * Props du composant React: EmailValidationJudgePageContent.
 * @property token : le token du juge à valider.
 */
interface EmailValidationJudgePageProps{
    token: string | undefined;
}

/**
 * Variables d'état du composant React: EmailValidationJudgePageContent.
 * @property response : la réponse de l'API.
 * @property judge : le juge à valider.
 * @property categories : les catégories de programmes d'études.
 * @property error : les erreurs de validation du formulaire.
 * @property sendSuccess : si l'envoi a réussi ou non.
 */
interface EmailValidationJudgePageStates{
    response: string | null;
    judge:Judge;
    categories:Category[];
    error:string[];
    sendSuccess:boolean;
}

/**
 * Contenu de la page de validation de l'adresse courriel d'un juge.
 * Ne pas utiliser directement cette classe, utiliser EmailValidationJudgePage pour pouvoir récupérer le token.
 * @author Jean-Philippe Bourassa
 */
export default class EmailValidationJudgePageContent extends React.Component<EmailValidationJudgePageProps,EmailValidationJudgePageStates> {
    constructor(props:EmailValidationJudgePageProps){
        super(props)

        /**
         * Initialisation des variables.
         */
        this.state = {
            response:null,
            judge:{
                id:0,
                email:"",
                firstName:"",
                lastName:"",
                category:"",
                pictureConsent:false,
                pwd:"",
                pwdconfirm:"",
                blacklisted:false,
                activated:false
            },
            categories:[],
            error:[],
            sendSuccess:false
        }

        // Sert à lier la méthode à l'instance du composant React.
        this.handleChangeForm = this.handleChangeForm.bind(this);
    }

    /**
     * Permet de modifier le state titleStand lors d'un changement dans le champs.
     */
    handleChangeForm(event:any, key:string){
        let judge : any = {...this.state}
        judge.judge[key] = event
        this.setState(judge)
    }

    /**
     * Fonction qui permet d'aller chercher les catégories à l'API
     * Tristan Lafontaine
     */
    async getCategory() {
    const response = await SignUpJudgeService.tryGetCategory()
    if (response.error){
        ShowToast(response.error!,5000,"error","top-center",false);
    }else{
        if(response.data){
            var categoriesData = response.data
            if (categoriesData){
                this.setState({categories:categoriesData})
            }
        }
    }
    }

    /**
     * Fonction qui permet d'aller chercher l'utilisateur à l'API
     * Jean-Philippe Bourassa
     */
    async getUser() {
    if (!this.props.token) return;

    const response = await SignUpJudgeService.tryGetJudge(this.props.token)
    if (response.error) {
        ShowToast(response.error!,5000,"error","top-center",false);
    } else {
        if (response.data) {
            var judgeData = response.data
            if (judgeData) {
                this.setState({judge:judgeData})
            }
        }
    }
    }

    /**
     * Fonction qui permet d'envoyer le formulaire à l'API
     * @author Jean-Philippe Bourassa
     */
    async onSubmit() {
        const response = await SignUpJudgeService.tryPostJudge(this.state.judge)
        if (response.error) {
            var error = response.error
            if (error) {
                if (error === TEXTS.api.errors.communicationFailed) {
                    this.setState({error: Array(error)})
                } else {
                    const results : string[] = Object.values(error)
                    this.setState({error: results})
                }
                window.scrollTo(0, 0)
            }
        } else {
            if (response.data) {
                var apiResponse = response.data
                if (apiResponse) {
                    const results : string[] = Object.values(apiResponse)
                    if (results[0] === "Ajout réussi") {
                        this.setState({error: []})
                        this.setState({sendSuccess:true})
                    } else {
                        window.scrollTo(0, 0)
                        this.setState({error: results})
                    }
                }
            }
        }
    }

    public render() {
        return (
            <Box className="centeredContainer">
                <div data-testid="emailValidationJudge" className="formContainer">
                    <ValidatorForm
                        onSubmit={()=>this.onSubmit()}
                    >
                        <h1 className={styles.title}>{TEXTS.signUpJudge.title}</h1>
                        <Box className={styles.paddingPaperTop}>
                            <div>
                            <JudgeVerificationForm judge={this.state.judge} handleChangeForm={this.handleChangeForm} categories={this.state.categories} firstname={this.state.judge.firstName} lastname={this.state.judge.lastName} />
                                <br/>
                            </div>
                        </Box>
                        <Button variant={INPUT_VARIANT} className={styles.boutonMembre} onClick={()=>this.onSubmit()}>Soumettre</Button>
                    </ValidatorForm>
                    <br/>
                </div>
            </Box>  
        )
    }
}