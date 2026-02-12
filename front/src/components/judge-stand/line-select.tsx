import React from 'react';
import { InputLabel, Select, MenuItem, SelectChangeEvent, FormControl } from '@mui/material';
import JugeInfo from '../../types/juge-stand/jugeInfo';
import { ShowToast, createRandomKey } from '../../utils/utils';
import JugeStandService from '../../api/juge-stand/jugestandService';
import IAssignation from '../../types/juge-stand/IAssignation';
import StandInfo from '../../types/juge-stand/standInfo';

interface LineSelectProps {
    stands: StandInfo[];
    leJuge: JugeInfo;
    placement: number;
    standsEval: IAssignation[];
    handleChangeAssignation: (evaluation : IAssignation) => void;
    handleDeleteAssignation: (id: number) => void;
    verifyIfTeamIsAssignedMoreThan3Times: (standEvalArray: IAssignation[], stand_id: string) => boolean;
}

interface LineSelectState {
    standId: string;
    standSurveyId: number;
    evalId: number;
}

/**
 * Classe pour selectionner un juge
 * @author Christopher Boisvert, Alex Des Ruisseaux
 * @editor Xavier Houle
 */
export default class LineSelect extends React.Component<LineSelectProps, LineSelectState> {
    /**
     * constructeur de la classe
     * @param props tableau de juge disponibles, numero de categorie de stand
     */
    constructor(props: LineSelectProps) {
        super(props)
        this.state = {
            standId: "",
            standSurveyId: -1,
            evalId: -1, 
        }
    }
    // Charge les valeurs du props
    componentDidMount(): void {
        this.CheckEvals();
    }
    // Si l'état de la page a changé affiche l'assignation changée
    componentDidUpdate(prevProps: LineSelectProps) {
        if (prevProps.standsEval !== this.props.standsEval) {
            this.CheckEvals();
        }
    }

    /**
     * @author Xavier Houle
     * Affiche les assignations du select
     */
    CheckEvals() {
        this.props.standsEval.map((evals) => {
            if (evals.hour !== this.props.placement + 1)
                return;

            if (this.props.leJuge.id !== evals.judge_id)
                return;

            this.setState({standId: evals.stand_id });
            this.setState({evalId: evals.id});
        })
    }
    /**
     * appel de la methode GetConflict
     * @author Souleymane Soumaré 
     * @param {string} jugeId - nom du juge
     * @param {number} standId - numero de stand
     */
    async checkConflict(jugeId: string, standId: string) {
        await JugeStandService.GetConflict(jugeId, standId)
    }

    /**
     * @author Xavier Houle
     * S'occupe de supprimer l'assignation dans la base de données
     */
    async DeleteSurvey() {
        await JugeStandService.DeleteSurvey(this.state.evalId);
        this.props.handleDeleteAssignation(this.state.evalId);

        this.setState({evalId: -1});
    }

    /**
     * @author Xavier Houle
     * Créer une nouvelle interface IAssignation 
     * et envoye l'évaluation a ajouter ou modifier 
     * pour ensuite enregistrer les valeurs dans la base de données
     * @param standId Le numéro de l'équipe
     * @param surveyId L'id d'un formulaire d'évaluation
     */
    updateOrAddSurvey(standId: string, surveyId: number) {
        const jugeId = this.props.leJuge.id;
        const placement = this.props.placement + 1;

        const newEval: IAssignation = {
            "id" : this.state.evalId,
            "judge_id" : jugeId, 
            "stand_id" : standId,
            "survey_id" : surveyId,
            "hour" : placement,
        }
        
        this.props.handleChangeAssignation(newEval);
    }

    /**
     * @author Xavier Houle
     * S'occupe de vérifier que le numéro d'équipe existe et
     * ensuite envoye les valeurs à la fonction updateOrAddSurvey()
     * @param e La numéro d'équipe selectionner par l'utilisateur
     */
    handleChange(e: SelectChangeEvent<string>) {
        this.setState({ standId: e.target.value });

        this.props.stands.map((stand) => {
            if (stand.team_number != e.target.value)
                return;

            this.setState({ standSurveyId: stand.survey_id });

            this.updateOrAddSurvey(e.target.value, stand.survey_id);
        })

        this.checkConflict(this.props.leJuge.nom_complet, e.target.value);
    }
    /**
     * @author Xavier Houle
     * S'occupe de donner une ordre de prioriété 
     * Les équipes seront afficher en ordre croissant
     * Les équipes n'étant pas dans la même catégories du juges seront en dessous 
     * de ceux qui le sont
     * @returns L'ordre de priorité des numéros d'équipes
     */
    sortStand(): StandInfo[] {
        return this.props.stands.sort((stand1, stand2) => {

             if (this.props.leJuge.categories_id !== stand1.categories_id && this.props.leJuge.categories_id === stand2.categories_id) {
                return 1
            }

             if (this.props.leJuge.categories_id === stand1.categories_id && this.props.leJuge.categories_id !== stand2.categories_id) {
                return -1
            }

            if (stand1.team_number > stand2.team_number) {
                return 1;
            }

            if (stand1.team_number < stand2.team_number) {
                return -1;
            }

            return 0;
        })
    }

    /**
     * Affiche les numéros d'équipes en jaune lorsque le juge
     * n'est pas dans la même catégorie que le juge
     * @param stand Les informations des équipes
     * @returns Retourne la couleur a affiché
     */
    colorMenuItem(stand: StandInfo) {
        if (this.props.leJuge.categories_id !== stand.categories_id) {
            
            return "yellow"
            
        }

        return ""
    }


    render() {
        /**
         * filtre les juges selon la categorie du stand.
         * @author Déreck "The GOAT" Lachance 
         * @editor Xavier Houle
         */
        return (
            <FormControl sx={{ m: 1, minWidth: 75 }}>
                <InputLabel id="stand-selection-label">Équipes</InputLabel>
                <Select
                    id="stand-select"
                    labelId="label equipe-selection-label"
                    label="Équipes"
                    value={this.state.standId}
                    onChange={(e) => {
                        if (e.target.value != " ") {
                            this.handleChange(e)
                        } 
                        else {
                            this.DeleteSurvey();
                            this.setState({ standId: String(e.target.value) });
                        }
                    }}
                    sx= {{
                        backgroundColor: this.props.verifyIfTeamIsAssignedMoreThan3Times(this.props.standsEval, this.state.standId) ? "yellow" : ""
                    }}
                >
                    <MenuItem value=" " sx={{ height: "35px" }}>Aucune équipe</MenuItem>
                    {
                        this.sortStand().map((stand) => {
                            if (stand.team_number !== undefined) {
                                return (
                                    <MenuItem
                                        key={createRandomKey()}
                                        value={stand.team_number}
                                        style={{
                                            backgroundColor: this.colorMenuItem(stand)
                                        }}
                                        
                                    >
                                        {stand.team_number}
                                    </MenuItem>
                                );
                            }
                        })
                    }
                </Select>
            </FormControl>
        );

    }

}