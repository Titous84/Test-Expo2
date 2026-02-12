import { Card, CardActions, CardContent, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from '@mui/material';
import React from 'react';
import { IEvaluation } from '../../types/survey/IEvaluation';
import { createRandomKey } from '../../utils/utils';
import ButtonExposat from '../button/button-exposat';
import SurveySection from './survey-section';
import Paper from '@mui/material/Paper';
import styles from "./survey-components.module.css";
import SurveyService from '../../api/survey/surveyService';
import { TEXTS } from '../../lang/fr';

/**
 * @author Christopher Boisvert
 *  Etat de la classe SurveySection.
 * @param actualSurvey Id de l'évaluation affiché actuellement.
 * @param isShowingMenu Booléen indiquant si le menu s'affiche.
 */
interface SurveySectionState
{
    actualSurvey?:number,
    isShowingMenu:boolean
}

/**
 * @author Christopher Boisvert
 *  Paramètres passés en props à la classe SurveySection.
 * @param surveyList Liste des évaluations à afficher.
 * @param setAllCompletedSurvey Méthode qui permet de lancer la fermeture définitive de tous les évaluations.
 * @param handleChangeQuestion Méthode permettant de gérer les changements de valeur des questions.
 * @param changeName Méthode permettant de changer le nom de la page.
 */
interface SurveySectionProps
{
    surveyList:IEvaluation[];
    setAllCompletedSurvey:() => void;
    handleChangeCommentaire:(evaluationId:number, commentaire:string) => void;
    handleChangeQuestion:(questionId:number, sectionId:number, evaluationId:number, newScore:number) => void;
    changeName:(namePage:string) => void;
}

/**
 * @author Christopher Boisvert
 *  Classe permettant d'afficher la liste des évaluations.
 */
export default class SurveyList extends React.Component<SurveySectionProps, SurveySectionState> {

    /**
     *  Constructeur.
     * @param props Prend en paramètre un SurveySectionProps.
     */
    constructor(props:SurveySectionProps){
        super(props)
        this.state = {
            actualSurvey:undefined,
            isShowingMenu:true
        }
    }

    /**
     *  Fonction qui permet de générer le bouton permettant de fermer les formulaires.
     * @returns Retourne un composant de type ButtonExposat.
     */
    generateCompleteSurveyButton()
    {
        return <ButtonExposat className={styles.buttonFormSubmit} onClick={() => { this.closeSurvey() }}>{ TEXTS.survey.textCompleteSurvey }</ButtonExposat>;
    }

    /**
     *  Méthode asynchrone qui permet de fermer les formulaires définitivement.
     */
    async closeSurvey()
    {
        let total_survey = 0

        this.props.surveyList.forEach( async (survey) => { 
            let resultat = await SurveyService.closeSurvey(survey.id);
            if(resultat) total_survey++;
            if(total_survey === this.props.surveyList.length) this.props.setAllCompletedSurvey()
        })
    }

    /**
     *  Méthode qui permet d'afficher le menu.
     */
    showMenu()
    {
        this.props.changeName(TEXTS.survey.defaultNamePage);
        this.setState({
            isShowingMenu:true
        })
    }

    /**
     *  Fonction qui permet de calculer le score d'une évaluation selon les questions en mémoire.
     * @param evaluation Objet de type IEvaluation contenant le score des question à évaluer.
     * @returns Retourne le score.
     */
    getScore(evaluation:IEvaluation): number
    {
        let score : number = 0;
        evaluation.sections.forEach(section => {
            section.questions.forEach(question => {
                score += question.score;
            })
        })
        return score;
    }

    /**
     *  Méthode qui permet d'afficher une évaluation selon son id.
     * @param survey_id Id de l'évaluation.
     */
    showSurvey(survey_id:number)
    {
        let index = this.props.surveyList.findIndex((survey) => { return survey.id === survey_id })
        this.props.changeName(TEXTS.survey.textNamePageForm + this.props.surveyList[index].stand_name)
        this.setState({
            actualSurvey:index,
            isShowingMenu:false
        })
    }

    /**
    * Fonction qui permet de calculer la moyenne d'une évaluation.
    * @author Tommy Garneau
    * @param evaluation Objet de type IEvaluation le score des question à évaluer.
    * @returns Retourne la moyenne en pourcentage.
    */
    getAverageScore(evaluation: IEvaluation): number {
        let totalScore = 0;
        let totalQuestions = 0;
        let averageScore = 0;

        evaluation.sections.forEach((section) => {
            section.questions.forEach((question) => {
                totalScore += question.score;
                totalQuestions++;
            });
        });

        averageScore = (totalScore / (totalQuestions * 10)) * 100;

        return averageScore;
    }

    /**
     *  Fonction qui permet de générer la table des évaluations.
     * @returns Retourne un objet React contenant un table html contenant les évaluations.
     */
    generateTableSurvey()
    {
        return (
            <div>
                <TableContainer id={styles.table_survey} component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Nom de formulaire</TableCell>
                                <TableCell>Heure de l'évaluation</TableCell>
                                <TableCell>Score attribué</TableCell>
                                <TableCell>Actions</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                        {this.props.surveyList.map((row) => (
                            <TableRow
                            key={row.id}
                            sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                            >
                                <TableCell component="th" scope="row">
                                    {row.stand_name}
                                </TableCell>
                                <TableCell component="th" scope="row">
                                    {row.evaluation_start}
                                </TableCell>
                                <TableCell component="th" scope="row">
                                    {this.getAverageScore(row).toFixed(2)} %
                                </TableCell>
                                <TableCell component="th" scope="row">
                                <ButtonExposat className={styles.boutonGrilleEvaluation} key={createRandomKey()} onClick={() => { this.showSurvey(row.id) }}>{TEXTS.survey.textButtonDoSurvey}</ButtonExposat>
                                </TableCell>
                            </TableRow>
                        ))}
                        </TableBody>
                    </Table>
                </TableContainer>
                {this.props.surveyList.map((row => {
                    return (
                        <Card className={styles.card_survey} sx={{ maxWidth: 345 }}>
                            <CardContent>
                                <Typography gutterBottom variant="h5" component="div">
                                {row.stand_name}
                                </Typography>
                                <Typography variant="body2" color="text.secondary">
                                Heure de l'évaluation : {row.evaluation_start}
                                </Typography>
                                <Typography variant="body2" color="text.secondary">
                                Score : {this.getAverageScore(row).toFixed(2)} %
                                </Typography>
                            </CardContent>
                            <CardActions>
                                <ButtonExposat className={styles.boutonGrilleEvaluation} key={createRandomKey()} onClick={() => { this.showSurvey(row.id) }}>{TEXTS.survey.textButtonDoSurvey}</ButtonExposat>
                            </CardActions>
                        </Card>
                    );
                }))}
            </div>
        )
    }

    /**
     * Méthode qui permet d'afficher le composant.
     * @returns Retourne l'objet React de ce composant.
     */
    render()
    {
        if (this.state.isShowingMenu){
            return (
                <div data-testid="survey-list">
                    <h2>Vos évaluations à faire</h2>
                    { this.generateTableSurvey() }
                    { this.generateCompleteSurveyButton() }
                </div>
            )
        }

        return (
            <div data-testid="survey-list">
                <SurveySection 
                    commentaire={this.props.surveyList[this.state.actualSurvey!!].comments}
                    handleChangeCommentaire={this.props.handleChangeCommentaire}
                    handleChangeQuestion={this.props.handleChangeQuestion}
                    formId={this.props.surveyList[this.state.actualSurvey!!].id}
                    returnToMenu={this.showMenu.bind(this)}
                    listSection={this.props.surveyList[this.state.actualSurvey!!].sections}></SurveySection>
            </div>
        );
    }
}