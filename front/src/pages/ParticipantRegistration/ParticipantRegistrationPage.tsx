/**
 * @file Page d'inscription des participants.
 * @author Tristan Lafontaine
 */
import { Navigate } from 'react-router';
import { ValidatorForm } from 'react-material-ui-form-validator'
import { Box, Button, CircularProgress } from '@mui/material';
import AlertComposant from '../../components/alert/alert';
import TeamContactPerson from '../../components/signup/team-contact-person';
import TeamInformation from '../../components/signup/team-info';
import TeamMemberForm from '../../components/signup/team-member';
import APIResult from '../../types/apiResult';
import Category from '../../types/sign-up/category';
import IPage from "../../types/IPage";
import TeamInfo from '../../types/sign-up/team-info';
import SignUpService from '../../api/signUp/signUpService';
import TeamsListService from '../../api/TeamsList/TeamsListService';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import { MAX_LENGTH_EMAIL, MAX_LENGTH_FIRST_NAME, MAX_LENGTH_LAST_NAME } from '../../utils/constants';
import { ShowToast } from '../../utils/utils';
import { TEXTS } from '../../lang/fr';
import styles from "./ParticipantRegistrationPage.module.css"

/**
 * @constant {number} MIN_MEMBER - Le nombre minimum de membres par équipe.
 * @constant {number} MIN_CONTACT - Le nombre minimum de personnes ressources par équipe.
 * @constant {number} MAX_CONTACT - Le nombre maximum de personnes ressources par équipe.
 * @author Mathieu Sévégny
 */
const MIN_MEMBER = 2
const MIN_CONTACT = 1
const MAX_CONTACT = 2

/**
 * Structure d'un membre d'une équipe.
 * @property {string} firstName - Le prénom du membre.
 * @property {string} lastName - Le nom de famille du membre.
 * @property {string} numero_da - Le numéro de Dossier Administratif du membre.
 */
interface Member {
    firstName: string;
    lastName: string;
    numero_da: string;
}

/**
 * Structure d'un message de succès. Affiche les informations de l'équipe créée à l'utilisateur.
 * @property {string} title - Le titre du message de succès.
 * @property {string} description - La description du message de succès.
 * @property {string} category - La catégorie de l'équipe créée.
 * @property {string} year - L'année de l'équipe créée.
 * @property {Member[]} members - La liste des membres de l'équipe créée.
 */
interface SuccessMessage {
    title: string;
    description: string;
    category: string;
    year: string;
    members: Member[];
}

/**
 * Variables d'état du composant React: ParticipantRegistrationPage.
 * @property {TeamInfo} teamInfo - Les informations de l'équipe.
 * @property {Category[]} categories - La liste des catégories disponibles.
 * @property {number} maxMembers - Le nombre maximum de membres par équipe.
 * @property {string[]} error - La liste des erreurs à afficher.
 * @property {boolean} sendSuccess - Indique si l'inscription a réussi.
 * @property {boolean} activeCircularProcress - Indique si le chargement est en cours.
 * @property {SuccessMessage} successMessage - Le message de succès à afficher.
 */
interface ParticipantRegistrationPageState{
    teamInfo:TeamInfo,
    categories:Category[],
    maxMembers:number,
    error:string[],
    sendSuccess:boolean,
    activeCircularProcress:boolean,
    successMessage: SuccessMessage
}

/**
 * Page du formulaire d'inscription pour les participants.
 * @author Tristan Lafontaine
 */
export default class ParticipantRegistrationPage extends IPage<{}, ParticipantRegistrationPageState> {
    constructor(props: {}) {
        super(props)

        /**
         * Initialisation des variables d'état.
         */
        this.state = {
            /**
             * @author Mathieu Sévégny
             */
            teamInfo : {
                title:'',
                description:'',
                category:'', 
                year:'1re année',
                contactPerson:[
                    {email:"", fullName:""}
                ],
                members:[
                    {numero_da:"",firstName:"",lastName:"",pictureConsent:0},
                    {numero_da:"",firstName:"",lastName:"",pictureConsent:0}
                ]
            },
            categories:[],
            maxMembers:8, // Nombre max de membres par équipe : 8
            error:[],
            sendSuccess: false,
            successMessage: {
                title: '',
                description: '',
                category: '',
                year: '',
                members: []
            },
            activeCircularProcress: false,
        }

        /**
         * Sert à lier la méthode à l'instance du composant React.
         * @author Tristan Lafontaine
         */
        this.handleChangeForm = this.handleChangeForm.bind(this);
    }

    /**
     * Ajoute un membre à un tableau
     * Mathieu Sévégny
     */
    addPerson(type:"members" | "contactPerson"){
        let oldState : any = {...this.state.teamInfo}

        //Prend le bon tableau
        let array : any[] = Array.from(oldState[type]);

        //Crée le bon object en fonction du type demandé
        if (type === "contactPerson") array.push({numero_da:"",fullName:""})
        else array.push({numero_da:"",firstName:"",lastName:"",pictureConsent:0})

        oldState[type] = array;

        //Modifie le state
        this.setState(prevState =>{ 
            let teamInfo = Object.assign({}, prevState.teamInfo);
                teamInfo = oldState
                return {teamInfo};
        })
    }

    /**
     * Enlève un membre à un tableau
     * Mathieu Sévégny
     */
    removePerson(type:"members" | "contactPerson"){
        let oldState : any = {...this.state.teamInfo}

        //Prend le bon tableau
        let array : any[] = Array.from(oldState[type]);

        //Enlève la dernière personne de la liste
        array.pop();

        oldState[type] = array;

        //Modifie le state
        this.setState(prevState => {
            let teamInfo = Object.assign({}, prevState.teamInfo);
            teamInfo = oldState;
            return {teamInfo};
        })
    }

    // Permet de modifier le state titleStand lors d'un changement dans le champs
    //Tristan Lafontaine
    handleChangeForm(event:any, key:string) {
        let teamInfo : any = {...this.state}
        teamInfo.teamInfo[key] = event
        if (key === "category"){
            teamInfo["maxMembers"] = this.state.categories.filter(category => category.name === event)[0].max_members
        }
        this.setState(teamInfo)
    }

    /**
     * Vérification personnaliser
     * Tristan Lafontaine
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
        //  Vérifier la longeur du champs prénom
        ValidatorForm.addValidationRule('maxLenghtFirstName', (value) => {
            if (value.length > MAX_LENGTH_FIRST_NAME) {
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
    }

    /**
     * Réagit aux changements dans les formulaires des membres.
     * @param number Numéro du membre
     * @param key Clé de la propriété
     * @param value Nouvelle valeur de la propriété
     * Mathieu Sévégny
     */
    handleChangeTeamMemberForm(number:number,key:string,value:any){
        //Cherche le tableau du type choisi
        let array : any[] = Array.from(this.state.teamInfo.members);

        //Cherche la personne dans le tableau
        let person : any = {...array[number-1]}
        
        //Change la valeur de la personne
        person[key] = value

        //Modifie la personne dans le tableau
        array[number-1] = person;

        //Modifie le state
        this.setState(prevState =>{ 
            let teamInfo = Object.assign({}, prevState.teamInfo);
            teamInfo.members = array
            return {teamInfo};
        })
    }

    /**
     * Réagit aux changements dans les formulaires des personnes ressources.
     * @param number Numéro du membre
     * @param key Clé de la propriété
     * @param value Nouvelle valeur de la propriété
     * Mathieu Sévégny
     */
    handleChangeContactForm(number:number,key:string,value:any){
        //Cherche le tableau du type choisi
        let array : any[] = Array.from(this.state.teamInfo.contactPerson);

        //Cherche la personne dans le tableau
        let person : any = {...array[number-1]}
        
        //Change la valeur de la personne
        person[key] = value

        //Modifie la personne dans le tableau
        array[number-1] = person;

        //Modifie le state
        this.setState(prevState => { 
            let teamInfo = Object.assign({}, prevState.teamInfo);
                teamInfo.contactPerson = array
                return {teamInfo};
        })
    }

    //Permet d'enlever l'erreur des champs quand il respecte les critères
    //Tristan Lafontaine
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse courriel
        ValidatorForm.removeValidationRule('maxLenghtEmail');
        // Retir l'erreur pour le champs prénom
        ValidatorForm.removeValidationRule('maxLenghtFirstName');
        // Retir l'erreur pour le champs nom famille
        ValidatorForm.removeValidationRule('maxLenghtLastName');
    }

    /**
     * Génère les formulaires de membres.
     * @returns Un formulaire pour chaque membre.
     * Mathieu Sévégnyemail
     */
    generateTeamMemberForms(){
        let counter = 0;
        return this.state.teamInfo.members.map(teamMember => {
            counter++;
            return <TeamMemberForm key={"member"+String(counter)}  handleChangeTeamMember={(n,k,v) => this.handleChangeTeamMemberForm(n,k,v)}
            number={counter} teamMember={teamMember} />
        })
    }

    /**
     * Génère les formulaires des personnes ressources.
     * @returns Un formulaire pour chaque personne ressource.
     * @author Mathieu Sévégny
     */
    generateContactPersonForms(){
        let counter = 0;
        return this.state.teamInfo.contactPerson.map(contactPerson => {
            counter++;
            return <TeamContactPerson key={"contact"+String(counter)} handleChangeContact={(n,k,v) => this.handleChangeContactForm(n,k,v)}
            number={counter} contactPerson={contactPerson} />
        })
    }

    /**
     * Génère les alerts lors d'erreur avec l'API ou fait la redirection vers la page de succès
     * @returns une alert pour chaque erreur
     * @author Tristan Lafontaine
     * 
     * @description Cette fonction génère une alerte pour chaque erreur dans le state error. Si l'inscription est réussie, elle redirige l'utilisateur vers la page de succès
     */
    generateAlert(){
        let counter = 0;
        if(this.state.error.length > 0){
            return this.state.error.map(error => { // Pour chaque erreur, on crée une alerte
                counter++;
                return <AlertComposant key={"alert"+String(counter)} typeAlert="error" errorMessage={error} titleAlert="Erreur" />
            })
        }
        else if(this.state.sendSuccess === true){ // Si l'inscription est réussie, on redirige l'utilisateur vers la page de succès
        return <Navigate replace to="/inscription-reussie" state={{ successMessage: this.state.successMessage }} />;
        } 
    }

    /**
     * Fonction qui permet d'aller chercher les categories à l'API
     * Tristan Lafontaine
     */
    async getCategory() {
        const response = await SignUpService.tryGetCategory()
        if (response.error) {
            ShowToast(response.error!,5000,"error","top-center",false)
        } else {
            if(response.data){
                var categoriesData = response.data
                if (categoriesData){
                    this.setState({categories:categoriesData})
                }
            }
        }
    }

    /**
     * Fonction qui permet de générer le numéro d'équipe lors de l'inscription
     * (Pas encore complètement fonctionnelle : l'incrémentation ne fonctionne pas
     * mais le numéro d'équipe est généré correctement)
     * 
     * @param categoryName Nom de la catégorie
     * @returns Le numéro d'équipe généré
     */
    private async generateTeamNumber(categoryName: string): Promise<string> {
    
        // Vérifie si les catégories sont chargées
        const category = this.state.categories.find(cat => cat.name === categoryName);
        // Si la catégorie n'est pas trouvée, on retourne un numéro par défaut
        const acronym = category?.acronym || "GEN";

        // On récupère la liste des équipes existantes
        const teamsResponse = await TeamsListService.tryGetTeamsMembersConcats();
        let count = 0; // Compteur pour le nombre d'équipes existantes
    
        if (teamsResponse.data && Array.isArray(teamsResponse.data)) {
            // Compte les équipes de la même catégorie
            count = teamsResponse.data.filter((team: any) => team.category === categoryName).length;
        }

        const teamNumber = `${acronym}${count + 1}`; // Incrémente pour la nouvelle équipe
        return teamNumber;
    }

    /**
     * Fonction qui permet d'envoyer le formulaire à l'API
     * @author Tristan Lafontaine
     * 
     * @description Cette fonction envoie les données du formulaire à l'API pour l'inscription d'une équipe. Si l'ajout est réussi, elle construit un message de succès et l'affiche. Sinon, en cas d'erreur, elle affiche les messages d'erreur correspondants
     */
    async onSubmit() {
        this.setState({activeCircularProcress:true}) // Activé le rendu de la progression de la soumission

        // Permet d'afficher un message si le form est tenté d'être soumis avant que les catégories soient chargées
        if (!this.state.categories || this.state.categories.length === 0) {
            ShowToast("Les catégories ne sont pas encore chargées. Veuillez réessayer dans quelques secondes.", 5000, "warning", "top-center", false);
            this.setState({activeCircularProcress:false});
            return;
        }

        const team_number = await this.generateTeamNumber(this.state.teamInfo.category);

        const teamInfoWithNumber = {
            ...this.state.teamInfo,
            team_number
        };

        const response: APIResult<any> = await SignUpService.tryPostTeam(teamInfoWithNumber) // Envoi des données à l'API

        // Si la réponse contient une erreur et pas de données
        if (response.error && !response.data) {
            var error = response.error
            if (error) {
                if (error === TEXTS.api.errors.communicationFailed) {
                    this.setState({error: Array(error)})
                } else {
                    const results: string[] = Object.values(error)
                    this.setState({error: results})
                }
                window.scrollTo(0, 0)
                this.setState({activeCircularProcress:false})
            }
        } else {
            // Si la réponse contient des données 
            if (response.data) {
                var apiResponse = response.data
                if (apiResponse) {
                    const results: string[] = Object.values(apiResponse)
                    if (results[0] === "Ajout réussi") { // Si l'ajout est réussi
                        this.setState({ error: [] });

                        // Construction du message de succès
                        const teamInfo = this.state.teamInfo;
                        const successMessage = {
                            title: teamInfo.title,
                            description: teamInfo.description,
                            category: teamInfo.category,
                            year: teamInfo.year,
                            members: teamInfo.members.map(member => ({
                                firstName: member.firstName,
                                lastName: member.lastName,
                                numero_da: member.numero_da
                            }))
                        };
                        
                        // Affichage du message de succès
                        this.setState({ sendSuccess: true, successMessage: successMessage });
                    } else {
                        window.scrollTo(0, 0)
                        this.setState({error: results})
                    }
                }
                this.setState({activeCircularProcress:false})
            } else {
                // Si la réponse contient une erreur inconnue
                this.setState({error: ["Erreur inconnue."]})
                this.setState({activeCircularProcress:false})
            }
        }
    }

    /**
     * Permet de scroller au début de la page
     */
    error(){
        window.scrollTo(0, 0)
    }

    /**
     * Permet de faire un rendu de la progression de la soumission.
     */
    circluarProgess(){
        return(
            <div className={`${styles.pageFullScreen} ${styles.flexTitle}`}>
                <CircularProgress size={50} color="inherit"/>
            </div>
        )
    }

    //Tristan Lafontaine
    public render() {
        return (
            <>
                { this.state.activeCircularProcress === false ? (
                    <Box className="centeredContainer">
                        <div data-testid="inscription" className="formContainer">
                            <ValidatorForm noValidate onSubmit={()=>this.onSubmit()}>
                                {this.generateAlert()} 
                                <h1 className={styles.title}>{TEXTS.signup.title}</h1>
                                <Box className={styles.paddingPaperTop}>
                                    <div>
                                        <TeamInformation teamInfo={this.state.teamInfo} handleChangeForm={this.handleChangeForm} categories={this.state.categories}/>
                                        <div className={styles.paddingEntreCarre}>
                                            {this.generateContactPersonForms()}
                                            <br/>
                                            <div className={styles.center}>
                                                {/* Bouton d'ajout de personne ressource */}
                                                {/* Mathieu Sévégny */}
                                                {this.state.teamInfo.contactPerson.length < MAX_CONTACT && 
                                                    <Button
                                                        variant={INPUT_VARIANT}
                                                        className={styles.boutonMembre}
                                                        onClick={()=>this.addPerson("contactPerson")}
                                                    >
                                                        {TEXTS.signup.information.buttonContactPerson.add}
                                                    </Button>
                                                }

                                                {/* Bouton de retrait de personne ressource */}
                                                {/* Mathieu Sévégny */}
                                                {this.state.teamInfo.contactPerson.length > MIN_CONTACT && 
                                                    <Button
                                                        variant={INPUT_VARIANT}
                                                        className={styles.boutonMembre}
                                                        onClick={()=>this.removePerson("contactPerson")}
                                                    >
                                                        {TEXTS.signup.information.buttonContactPerson.remove}
                                                    </Button>
                                                }
                                            </div>
                                        </div>
                                    </div>

                                    <h2>{TEXTS.signup.textMember.title}</h2>
                                    <h3>{TEXTS.signup.textMember.text}</h3>
                                    <div className={styles.paddingEntreCarre}>
                                        {/* Mathieu Sévégny */}
                                        {this.generateTeamMemberForms()}
                                        <br/>
                                        <div className={styles.center}>
                                            {/* Bouton d'ajout de membre */}
                                            {/* Mathieu Sévégny */}
                                            {this.state.teamInfo.members.length < this.state.maxMembers && 
                                                <Button
                                                    variant={INPUT_VARIANT}
                                                    className={styles.boutonMembre}
                                                    onClick={() => this.addPerson("members")}
                                                >
                                                    {TEXTS.signup.buttons.addMember}
                                                </Button>
                                            }

                                            {/* Bouton de retrait de membre */}
                                            {/* Mathieu Sévégny */}
                                            {this.state.teamInfo.members.length > MIN_MEMBER && 
                                                <Button
                                                    variant={INPUT_VARIANT} 
                                                    className={styles.boutonMembre} 
                                                    onClick={() => this.removePerson("members")}
                                                >
                                                    {TEXTS.signup.buttons.removeMember}
                                                </Button>
                                            }
                                        </div>
                                        <br/>
                                    </div>
                                </Box>

                                <div className={styles.center}>
                                    <Button type="submit" variant={INPUT_VARIANT} className={styles.boutonMembre} onClick={this.error}>Soumettre </Button>
                                </div>
                            </ValidatorForm>
                            <br/>
                        </div>
                    </Box>
                ) : (
                    this.circluarProgess()
                )}
            </>
        )
    }
}