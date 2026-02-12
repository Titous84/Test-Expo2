import AdministrationMainPage from "../pages/AdministrationMain/AdministrationMainPage";
import CreateJudgeSuccessful from "../pages/CreateJudgeSuccessful/createJudgeSuccessful";
import DevelopersListPage from "../pages/DevelopersList/DevelopersListPage";
import EmailEvaluationJudgeIndividually from "../pages/EmailEvaluationJudges/emailEvaluationJudgeIndividually";
import EmailValidationJudgePageContent from "../pages/EmailValidationJudge/EmailValidationJudgePage";
import { EmailValidationPage } from "../pages/EmailValidation/EmailValidationPage";
import EvaluationGridCreationPage from "../pages/EvaluationGridsList/EvaluationGridCreationPage";
import FeatureUnavailablePage from "../pages/FeatureUnavailable/FeatureUnavailablePage";
import ForgottenPasswordModificationPage from "../pages/ForgottenPasswordModification/ForgottenPasswordModificationPage"
import ForgottenPasswordPage from "../pages/ForgottenPassword/ForgottenPasswordPage";
import HomePage from "../pages/Home/HomePage";
import InformationsPage from "../pages/Informations/InformationsPage";
import JudgeCreationPage from "../pages/JudgeCreation/JudgeCreationPage"
import JudgeEvaluationsListPage from "../pages/JudgeEvaluationsList/JudgeEvaluationsListPage";
import JudgesEmailsSendingPage from "../pages/EmailEvaluationJudges/JudgesEmailsSendingPage";
import LoginPage from "../pages/Login/LoginPage";
import LogOutPage from "../pages/LogOut/LogOutPage";
import ParticipantRegistrationPage from "../pages/ParticipantRegistration/ParticipantRegistrationPage";
import TeamCreationSuccessPage from "../pages/TeamCreationSuccess/TeamCreationSuccessPage";
import TeamDetailsPage from "../pages/TeamsDetails/TeamDetailsPage";

/**
 * Un path contient son chemin, un nom pour identifier,
 * l'élément du chemin, la position dans la navbar (peut ne pas être affiché)
 * et la liste de rôles pouvant accéder à cette page (["*"] pour tous les rôles)
 */
export interface Path{
    path: string;
    name: string;
    element?: any;
    position: "Left"|"Right"|"Hidden";
    roles?: RoleName[]
}
// export type RoleName = "Guest"|"Admin"|"Juge"|"Correcteur"|"Participants"|"Votant"|"CocardesSAT"|"*"
export type RoleName = "Guest"|"Admin"|"Juge"|"Correcteur"|"Participants"|"*"

/**
 * Liste des pages principales du site
 * path: Débute avec un "/"
 */
export const pages : Path[] = [
    // Pages générales
    {path:"/", name:"Accueil", element:HomePage, position:"Left", roles:["*"]}, 
    {path:"/informations", name:"Informations", element:InformationsPage, position:"Left", roles:["*"]},
    {path:"/liste-developpeurs", name:"Liste développeurs", element:DevelopersListPage, position:"Hidden", roles:["*"]},
    // Page d'inscription utilisée par les participants
    {path:"/inscription", name:"Inscription", element:ParticipantRegistrationPage, position:"Hidden", roles:["*"]},
    {path:"/inscription-reussie", name:"Inscription réussie", element:TeamCreationSuccessPage, position:"Hidden", roles:["*"]},
    // Pages de connexion utilisées par les administrateurs
    {path:"/connexion", name:"Connexion", element:LoginPage, position:"Right", roles:["Guest"]},
    {path:"/deconnexion", name:"Déconnexion", element:LogOutPage, position:"Right", roles:["Admin","Correcteur","Participants"]},
    {path:"/mot-de-passe-oublie", name:"Mot de passe oublié", element:ForgottenPasswordPage, position:"Hidden", roles:["Guest"]},
    {path:"/modifier-mot-de-passe-oublie", name:"Modifier mot de passe oublié", element:ForgottenPasswordModificationPage, position:"Hidden", roles:["*"]},
    
    // Pages d'administration
    {path:"/administration", name:"Administration", element:AdministrationMainPage, position:"Right", roles:["Admin"]},
    {path:"/indisponible", name:"Fonction non disponible", element:FeatureUnavailablePage, position:"Hidden", roles:["Admin"]},
    // Équipes
    {path:"/details-equipe/:teamName", name:"Détails de l'équipe", element:TeamDetailsPage, position:"Hidden", roles: ["Admin"]},
    // Juges
    {path:"/inscription-juge", name:"Inscription Juge", element:JudgeCreationPage, position:"Hidden", roles:["Admin"]},{path:"/inscription-juge-reussi",name:"Inscription réussi", element:CreateJudgeSuccessful, position:"Hidden", roles:["*"]},
    {path:"/envoiEvaluationsJuges", name:"Envoi des évaluations aux juges", element:JudgesEmailsSendingPage, position:"Hidden", roles:["Admin"]},
    {path:"/envoiEvaluationsJugeIndividuelle",name:"Envoi des évaluations à un juge",element:EmailEvaluationJudgeIndividually,position:"Hidden",roles:["Admin"]},
    // Grilles d'évaluation pour les juges
    {path:"/gestion-grille-evaluation/formulaire", name:"Création d'une grille d'évaluation", element:EvaluationGridCreationPage, position:"Hidden", roles:["Admin"]},
    {path:"/gestion-grille-evaluation/formulaire/:id", name:"Modification d'une grille d'évaluation", element:EvaluationGridCreationPage, position:"Hidden", roles:["Admin"]},
    
    // Pages utilisées par les juges
    {path:"/validation-juge/:token", name:"Validation juge", element:EmailValidationJudgePageContent, position:"Hidden", roles:["*"]},
    {path:"/effectuer-evaluation/:token", name:"Effectuer une évaluation", element:JudgeEvaluationsListPage, position:"Hidden", roles:["*"]},

    // ?
    {path:"/validation-courriel/:token", name:"Validation courriel", element:EmailValidationPage, position:"Hidden", roles:["*"]},
]