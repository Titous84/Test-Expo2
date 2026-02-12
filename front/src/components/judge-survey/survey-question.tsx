import { Slider, TextField } from '@mui/material';
import React from 'react';
import SurveyService from '../../api/survey/surveyService';
import { IJudgeQuestion } from '../../types/survey/IJudgeQuestion';
import styles from "./survey-components.module.css";

/**
 * @author Christopher Boisvert
 *  Props de la classe SurveyQuestion.
 * @param IQuestion Question à afficher..
 * @param sectionId Id de la section associé à la question.
 * @param formId Id du formulaire associé à la question.
 * @param handleChangeQuestion Méthode permettant de changer le score de la question.
 */
interface SurveyQuestionProps {
    question: IJudgeQuestion,
    sectionId: number,
    formId: number,
    handleChangeQuestion: (questionId: number, sectionId: number, evaluationId: number, newScore: number) => void;
}

/**
 * @author Christopher Boisvert
 * @author Jean-Christophe Demers
 *  Affiche une liste des questions sous la forme d'un slider ou d'un input numérique. Il est possible d'utiliser les deux en mêmes temps également.
 */
export default class SurveyQuestion extends React.Component<SurveyQuestionProps, {}> {

    /**
     *  Méthode asynchrone qui permet de configurer le score de la question.
     * @param score Score de la question.
     */
    async setScore(score: number) {
        await SurveyService.setScoreOfSurveyQuestion(score, this.props.formId, this.props.question.id)
    }

    /**
     *  Méthode qui permet de gérer les changements de valeur dans les champs des questions.
     * @param event Événement natif du DOM.
     * @param newValue Nouvelle valeur entré par l'utilisateur.
     */
    handleChange(event: any, newValue: any) {
        if (newValue) {
            newValue = Number(newValue);
        }
        else {
            newValue = 0;
        }
        this.props.handleChangeQuestion(this.props.question.id, this.props.sectionId, this.props.formId, newValue);
    }

    /**
     *  Méthode qui permet de gérer les changements de valeur des questions via le slider.
     * @param event Événement natif du DOM.
     * @param newValue Nouvelle valeur entré par l'utilisateur.
     */
    commitChange(event: any, newValue: any) {
        this.setScore(newValue)
        this.props.handleChangeQuestion(this.props.question.id, this.props.sectionId, this.props.formId, newValue)
    }

    /**
     *  Fonction qui permet de générer le slider et le bouton input.
     * @returns Retourne un objet React.
     */
    generateSliderInput() {
        return (
            <div className={styles.question} data-test-id="surver-question-slider">
                <p>{this.props.question.criteria}</p>
                <Slider
                    className={styles.slider}
                    aria-label="Note"
                    value={this.props.question.score}
                    valueLabelDisplay="on"
                    step={0.5}
                    marks
                    min={0}
                    max={10}
                    onChangeCommitted={(event, newValue) => { this.commitChange(event, newValue) }}
                    onChange={(event, newValue) => { this.handleChange(event, newValue) }}
                />
            </div>
        );
    }

    render() {
        return (
            <div data-testid="survey-question">
                {this.generateSliderInput()}
            </div>
        );
    }
}
