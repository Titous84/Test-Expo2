import { Alert, CircularProgress, TextField } from '@mui/material';
import React from 'react';
import { TEXTS } from '../../lang/fr';
import HorizontalNonLinearStepper from '../stepper/horizontal-non-linear-stepper';
import { IJudgeSection } from '../../types/survey/IJudgeSection';
import SurveyQuestion from './survey-question';
import SurveyService from '../../api/survey/surveyService';
import ButtonExposat from '../button/button-exposat';

/**
 * @author Christopher Boisvert
 *  Propriétés de la classe SurveySection.
 * @param listSection Liste des sections à afficher.
 * @param commentaire le commentaire de l'évaluation.
 * @param formId Id de l'évaluation.
 * @param returnToMenu Méthode permettant de retourner au menu des évaluations.
 * @param handleChangeQuestion Méthode permettant de changer le score d'une question.
 */
interface SurveySectionProps {
    listSection: IJudgeSection[],
    commentaire: string,
    formId: number;
    returnToMenu: () => void;
    handleChangeCommentaire: (evaluationId: number, commentaire: string) => void;
    handleChangeQuestion: (questionId: number, sectionId: number, evaluationId: number, newScore: number) => void;
}

/**
 * @author Christopher Boisvert
 *  État de classe SurveySection.
 * @param actualSection Section affiché actuellement.
 */
interface SurveySectionState {
    sending: boolean,
    prevcommentaire: string,
    actualSection: number | undefined
}

/**
 * @author Christopher Boisvert
 * @author Jean-Christophe Demers
 *  Affiche les sections du formulaire avec des boutons pour passer d'une section à une autre.
 */
export default class SurveySection extends React.Component<SurveySectionProps, SurveySectionState> {

    /**
     *  Constructeur.
     * @param props Prend en paramètre un objet de type SurveySectionProps.
     */
    constructor(props: SurveySectionProps) {
        super(props)
        this.state = {
            sending: false,
            prevcommentaire: this.props.commentaire,
            actualSection: 0
        }
    }

    /**
     *  Fonction permettant de générer la section actuelle à afficher.
     * @returns Retourne un objet React.
     */
    generateSection() {
        return (
            <div data-testid="survey-section">
                <HorizontalNonLinearStepper
                    steps={this.obtenirListeNomSection()}
                    setActualSection={this.setActualSection.bind(this)}
                    returnToMenu={this.props.returnToMenu.bind(this)}></HorizontalNonLinearStepper>
                <h2>{this.state.actualSection === undefined ? TEXTS.survey.commentaire : this.props.listSection[this.state.actualSection].name}</h2>
                {this.generationQuestionOfSection()}
                <h2>Points totaux: {this.afficherPointsTotauxAttribue()}/{this.afficherPointsTotauxFormulaire()}</h2>
            </div>
        );
    }

    /**
     *  Fonction permettant d'extraire des props le nom de toutes les sections.
     * @returns Retourne un tableau de string.
     */
    obtenirListeNomSection() {
        return this.props.listSection.map((section) => {
            return section.name;
        }).concat(TEXTS.survey.commentaire)
    }

    /**
     *  Fonction qui permet de générer les questions dans la section.
     * @returns Retourne un tableau d'objet React.
     */
    generationQuestionOfSection() {
        if (this.state.actualSection === undefined) {
            return <div>
                <textarea
                    id="outlined-number"
                    value={this.props.commentaire}
                    onChange={async (event) => { this.setComment(event.target.value) }}
                    maxLength={500}
                />
                {
                    this.state.sending ? 
                        <CircularProgress size={50} color="inherit"/> :
                        <ButtonExposat disabled={this.state.sending || this.state.prevcommentaire === this.props.commentaire} onClick={() => this.sendComment()} children={"Confirmer"} />
                }
            </div>;
        }
        return this.props.listSection[this.state.actualSection].questions?.map(question => {
            return <SurveyQuestion
                handleChangeQuestion={this.props.handleChangeQuestion}
                key={`form-${this.props.formId}-section-${this.props.listSection[this.state.actualSection!].id}-question-${question.id}`}
                sectionId={this.props.listSection[this.state.actualSection!].id}
                formId={this.props.formId}
                question={question}
            ></SurveyQuestion>;
        });
    }

    setComment(comment: string) {
        this.props.handleChangeCommentaire(this.props.formId, comment);
    }

    async sendComment() {
        if (this.state.prevcommentaire === this.props.commentaire) {
            return;
        }
        this.setState({
            sending: true,
        });

        let result = await SurveyService.setCommentOfSurvey(this.props.commentaire, this.props.formId);
        if (result.error) {
            this.setState({
                sending: false,
            })
        } else {
            this.setState({
                sending: false,
                prevcommentaire: this.props.commentaire
            })
        }
    }

    /**
     *  Méthode qui permet de changer la section actuelle.
     * @param actual_section_id Id de la nouvelle section à afficher.
     */
    setActualSection(actual_section_id: number) {
        this.setState({
            actualSection: actual_section_id >= this.props.listSection.length ? undefined : actual_section_id
        });
    }

    /**
     *  Fonction qui regarde si le formulaire contient des sections.
     * @returns Retourne une valeur numérique.
     */
    surveyHasSection() {
        return this.props.listSection.length > 0
    }

    /**
     *  Fonction qui permet de déterminer si on est à la dernière section.
     * @returns Retourne une valeur numérique.
     */
    isLastSection() {
        return this.state.actualSection === this.props.listSection.length - 1
    }

    /**
     *  Fonction qui permet de déterminer si on n'est pas à la première section.
     * @returns Retourne une valeur numérique.
     */
    isNotFirstSection() {
        return this.state.actualSection === undefined || this.state.actualSection > 0
    }

    /**
     * @author Tommy Garneau
     *  Fonction qui permet de calculer le nombre de points totaux attribués aux questions par les juges.
     * @returns Retourne le nombre de points totaux attribués par les juges.
     */
    afficherPointsTotauxAttribue() { 
        let pointsTotaux = 0;
        this.props.listSection.forEach(question => {
               question.questions.forEach(score => {
                    pointsTotaux += score.score;
               });
          });
          return pointsTotaux;
    }

    /**
     * @author Tommy Garneau
     *  Fonction qui permet de calculer le nombre de points totaux de la valeur maximale de toute les questions du formulaire.
     * @returns Retourne les points totaux additionné de la valeur maximale de toute les questions.
     */
    afficherPointsTotauxFormulaire() { 
        let pointsTotaux = 0;
        this.props.listSection.forEach(question => {
               pointsTotaux += question.questions.length * 10
          });
          return pointsTotaux;
    }

    /**
     *  Fonction permettant d'effectuer l'affichage la section.
     * @returns Retourne un objet de type React.
     */
    render() {
        return (
            <div data-testid="survey-wrapper-section">
                {!this.surveyHasSection() && <Alert severity="error">{TEXTS.survey.noSurveySectionFound}</Alert>}
                {this.surveyHasSection() && this.generateSection()}
            </div>
        );
    }
}