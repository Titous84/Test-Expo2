import React from "react";
import { useParams } from "react-router";
import { Alert } from "@mui/material";
import SurveyList from "../../components/judge-survey/survey-list";
import Layout from "../../components/layout/layout";
import { IJudgeEvaluation } from "../../types/JudgeEvaluationsListPage/IJudgeEvaluation";
import { IJudgeQuestion } from "../../types/survey/IJudgeQuestion";
import SurveyService from "../../api/survey/surveyService";
import { TEXTS } from "../../lang/fr";

/**
 * Page où un juge peut voir la liste de ses évaluations.
 * @author Mathieu Sévégny
 */
export default function JudgeEvaluationsListPage(): JSX.Element {
    let { token } = useParams();

    // Passer le paramètre 'token' provenant de l'URL dans les props.
    return (
        <JudgeEvaluationsListPageContent token={token}></JudgeEvaluationsListPageContent>
    );
}

/**
 * Props pour le composant React: JudgeEvaluationsListPageContent.
 * @author Christopher Boisvert
 * 
 * @property token Token UUID qui représente un identifiant unique d'un juge.
 */
interface JudgeEvaluationsListPageContentProps {
    token?:string
}

/**
 * Contenu de la page des évaluations d'un juge.
 * Ne pas utiliser directement cette classe, utiliser JudgeEvaluationsListPage pour pouvoir récupérer le token.
 * @author Christopher Boisvert
 */
export class JudgeEvaluationsListPageContent extends React.Component<JudgeEvaluationsListPageContentProps, IJudgeEvaluation> {

    /**
     * Constructeur.
     * @param props Prend en paramètre un JudgeSurveyProps.
     */
    constructor(props:JudgeEvaluationsListPageContentProps){
        super(props)

        this.state = {
            pageName:TEXTS.survey.defaultNamePage,
            isSearchingEvaluation: true,
            isEvaluationFound: false,
            isEvaluationCompleted:false,
            evaluationsList: []
        }

        this.changeName = this.changeName.bind(this)
        this.handleChangeCommentaire = this.handleChangeCommentaire.bind(this)
        this.handleChangeQuestion = this.handleChangeQuestion.bind(this)
        this.setAllCompletedSurvey = this.setAllCompletedSurvey.bind(this)
    }

    /**
     * @author Christopher Boisvert
     *  Après l'exécution du constructeur, cette fonction va s'exécuter afin d'aller chercher les formulaires d'évaluations.
     */
    componentDidMount()
    {
        this.getSurvey()
    }

    /**
     * @author Christopher Boisvert
     *  Permet d'aller obtenir dans l'api les formulaires d'évaluations avec le token UUID.
     */
    async getSurvey()
    {
        let result = await SurveyService.getAllSurveyByJudgeUUID(this.props.token!!)
        if(result.data?.length){
            this.setState({
                isEvaluationFound:true,
                isSearchingEvaluation:false,
                evaluationsList: result.data!!
            })
        }
        else
        {
            this.setState({
                isEvaluationFound:false,
                isSearchingEvaluation:false,
                evaluationsList: []
            })
        }
    }

    /**
     * Méthode permettant de changer dans le state le commentaire d'un formulaire.
     * @param evaluationId Id de l'évaluation.
     * @param newCommentaire Nouveau score de la question.
     */
    handleChangeCommentaire(evaluationId:number, newCommentaire:string)
    {
        let newListSurvey = this.state.evaluationsList.map((survey) => {
            if(survey.id === evaluationId){
                survey.comments = newCommentaire;
            }
            return survey;
        })
        this.setState({
            evaluationsList: newListSurvey
        })
    }

    /**
     * Méthode permettant de changer dans le state le score d'une question.
     * @param questionId Id de la question.
     * @param sectionId Id de la section.
     * @param evaluationId Id de l'évaluation.
     * @param newScore Nouveau score de la question.
     */
    handleChangeQuestion(questionId:number, sectionId:number, evaluationId:number, newScore:number)
    {
        let newListSurvey = this.state.evaluationsList.map((survey) => {
            let scoreSurvey = 0;
            survey.sections = survey.sections.map((section) => {
                let tableauQuestion:IJudgeQuestion[] = section.questions.map((question) => {
                    scoreSurvey += question.score;
                    if(survey.id === evaluationId && section.id === sectionId && question.id === questionId)
                    {
                        question.score = newScore;
                        return question;
                    }
                    else
                    {
                        return question;   
                    }
                })
                section.questions = []
                section.questions.push(...tableauQuestion);
                return section;
            })
            survey.score = scoreSurvey;
            return survey;
        })
        this.setState({
            evaluationsList: newListSurvey
        })
    }

    /**
     *  Fonction qui retourne la valeur de la variable isSurveySearching dans le state.
     * @returns Retourne vrai si la page cherche toujours un formulaire et faux dans le cas contraire.
     */
    isSurveySearching(): boolean
    {
        return this.state.isSearchingEvaluation
    }

    /**
     *  Fonction qui retourne la valeur de la variable isSurveyFound dans le state.
     * @returns Retourne vrai si un formulaire a été trouvé et faux dans le cas contraire.
     */
    isSurveyFound(): boolean
    {
        return this.state.isEvaluationFound
    }
    
    /**
     *  Fonction qui retourne la valeur de la variable isSurveyCompleted dans le state.
     * @returns Retourne vrai si tous les formulaires sont completés.
     */
    isAllSurveyCompleted(): boolean
    {
        return this.state.isEvaluationCompleted
    }

    /**
     *  Méthode qui permet de changer la variable d'etat isSurveyCompleted à true.
     */
    setAllCompletedSurvey()
    {
        this.setState({
            isEvaluationCompleted:true
        })
    }

    /**
     * @author Christopher Boisvert
     * @returns Retourne void.
     */
    changeName(namePage:string)
    {
        this.setState({
            pageName: namePage
        })
    }

    /**
     * @author Christopher Boisvert
     * @returns Retourne un objet JSX.Element contenant la page d'évaluation.
     */
    render() {
        return (
            <div data-testid="judge-survey">
                <Layout name={this.state.pageName}>
                    { this.isSurveySearching() && <Alert severity="warning">{ TEXTS.survey.isSearchingSurvey }</Alert>  }
                    { !this.isSurveySearching() && !this.isSurveyFound() && <Alert severity="error">{ TEXTS.survey.surveyNotFound }</Alert>  }
                    { !this.isSurveySearching() && this.isSurveyFound() && !this.isAllSurveyCompleted() && 
                      <SurveyList setAllCompletedSurvey={this.setAllCompletedSurvey} handleChangeCommentaire={this.handleChangeCommentaire} handleChangeQuestion={this.handleChangeQuestion} changeName={this.changeName} surveyList={this.state.evaluationsList}></SurveyList> 
                    }
                    { this.isAllSurveyCompleted() && <Alert severity="success">{ TEXTS.survey.surveyCompletedConfirmation }</Alert> }
                </Layout>
            </div>
        )
    }
}