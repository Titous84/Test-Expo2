import * as React from 'react';
import { Button, ButtonProps, Divider, styled, Table, TableBody, TableCell, TableContainer, TableHead, TableRow } from '@mui/material';
import { purple } from '@mui/material/colors';
import EvalHour from '../../components/judge-stand/eval-hour';
import StandRow from '../../components/judge-stand/stand-row';
import IAssignation from '../../types/juge-stand/IAssignation';
import JugeInfo from '../../types/juge-stand/jugeInfo';
import StandInfo from '../../types/juge-stand/standInfo';
import TimeSlots from '../../types/juge-stand/timeSlots';
import Category from '../../types/sign-up/category';
import JugeStandService from '../../api/juge-stand/jugestandService';
import SignUpJudgeService from '../../api/signUpJudge/signUpJudgeService';
import { ShowToast } from '../../utils/utils';

interface JudgesSchedulesPageState {
    stands: StandInfo[];
    standsEval: IAssignation[];
    juges: JugeInfo[];
    categories: Category[];
    hours: TimeSlots[],
    boolDialog: boolean;
    boolStand: boolean;
    boolLoading: boolean;
}

//suivre thème de couleur du site
const ColorButton = styled(Button)<ButtonProps>(({ theme }) => ({
    color: theme.palette.getContrastText(purple[500]),
    backgroundColor: 'black',
    '&:hover': {
        backgroundColor: 'black',
    },
}));

/**
 * Page qui s'occupe d'assigner les équipes aux juges et à une heure de passage spécifique
 * @author Xavier Houle
 */
export default class JudgesSchedulesPage extends React.Component<{}, JudgesSchedulesPageState> {
    constructor(props: {}) {
        super(props)
        this.state = {
            boolDialog: false,
            boolStand: false,
            stands: [],
            standsEval: [],
            juges: [],
            hours: [],
            categories: [],
            boolLoading: true,
        }
    }

    /**
     * Permet de charger toutes les valeurs requise pour la page
     * Le boolLoading est pour afficher les résultats avant que l'API est fini
     * @author Xavier Houle
     */
    async componentDidMount() {
        await Promise.all([
            this.loadJudge(),
            this.loadEvaluation(),
            this.loadTimeSlots(),
            this.loadStands(),
            this.loadCategory()
        ]);


        setTimeout(() => {
            this.setState({
                boolLoading: false
            })
        }, 500)
    }

    /** 
     * @function loadStands
     * @author Xavier Houle
     * S'occupe d'aller chercher le numéro d'équipe dans la BD
     * Si la réponse de l'API est inexistante affiche une erreur
     */
    async loadStands() {
        const response = await JugeStandService.GetStand();

        if (response.data?.length) {
            this.setState({ stands: response.data })
        }
        else {
            //Erreur
            ShowToast("une erreur est survenue veuillez contacter l'administration", 5000, "error", "top-center", false)
        }
    }

    /** 
     * @function loadTimeSlots
     * @author Xavier Houle
     * S'occupe d'aller chercher les heures de passages dans la BD
     * Si la réponse de l'API est inexistante affiche une erreur
    */
    async loadTimeSlots() {
        let response = await JugeStandService.GetAllTimeSlots();
        if (response.data?.length) {
            this.setState({
                hours: response.data.map((slot: TimeSlots) => {
                    return {
                        id: slot.id,
                        time: new Date('2021-01-01T' + slot.time)
                    }
                })
            })
        }
        else {
            ShowToast("une erreur est survenue veuillez contacter l'administration", 5000, "error", "top-center", false)
        }
    }

    /** 
     * @function loadCategory
     * @author Xavier Houle
     * S'occupe d'aller chercher les catégories dans la BD
     * Si la réponse de l'API est inexistante affiche une erreur
    */
    async loadCategory() {
        let response = await SignUpJudgeService.tryGetCategory();
        if (response.data?.length) {
            this.setState({
                categories: response.data
            });
        }
        else {
            ShowToast("une erreur est survenue veuillez contacter l'administration", 5000, "error", "top-center", false);
        }
    }

    /** 
     * @function loadEvaluation
     * @author Xavier Houle
     * S'occupe d'aller chercher les assignations existante dans la BD
     * Si la réponse de l'API est inexistante affiche une erreur
    */
    async loadEvaluation() {
        let response = await JugeStandService.GetAllEvaluations();

        if (response.data?.length) {
            this.setState({
                standsEval: response.data,
                boolStand: true
            })
        }
    }

    /** 
     * @function loadJudge
     * @author Xavier Houle
     * S'occupe d'aller chercher les juges existants dans la BD
     * Si la réponse de l'API est inexistante affiche une erreur
     */
    async loadJudge() {
        const resultat = await JugeStandService.GetJudge();
        if (resultat.data?.length) {
            //A fonctionné
            this.setState({ juges: resultat.data })
        }
        else {
            //Erreur
            ShowToast("une erreur est survenue veuillez contacter l'administration", 5000, "error", "top-center", false)
        }
    }

    // S'occupe d'ouvrir le dialogue qui permet de changer les heures de passages
    handleClickOpen = () => {
        this.setState({ boolDialog: true });
    };

    // S'occupe de fermer le dialogue qui permet de changer les heures de passages
    handleClose = () => {
        this.setState({ boolDialog: false });
    };

    /**
     * @function UpdateSurvey
     * @author Xavier Houle
     * @param evalId L'évaluation id à changer
     * @param stand_id Le numéro de l'équipe
     * @param survey_id L'id d'un formulaire 
     * S'occupe de modifier l'assignation d'une équipe avec
     * Le numéro d'équipe et le formulaire d'évaluation
     */
    async UpdateSurvey(evalId: number, stand_id: string, survey_id: number) {
        await JugeStandService.PatchStandSurvey(evalId, stand_id, survey_id);
    }

    /**
     * appel de la methode PushStandSurvey
     * @author Déreck "The GOAT" Lachance
     * @param {string} jugeId - id du juge
     * @param {number} standId - numero de stand
     * @editor Xavier Houle
     */
    async AddSurvey(jugeId: number, standId: string, surveyId: number, hour: number) {
        const response = await JugeStandService.PushStandSurvey(jugeId, standId, surveyId, hour)

        if (response.data) {
            return response.data;
        } else {
            ShowToast("Une erreur est survenue lors de l'ajout.", 5000, "error", "top-center", false);
        }
    }

    /**
     * @author Xavier Houle
     * @param standEvalArray L'array contenant tous les assignations
     * @param stand_id Le numéro de l'équipe
     * @returns Retourne vrai si une équipe est assignée plus de 3 fois
     */
    verifyIfTeamIsAssignedMoreThan3Times(standEvalArray: IAssignation[], stand_id: string) {
        const countAssignation = standEvalArray.filter(assignation => assignation.stand_id === stand_id).length;

        if (countAssignation > 3) {
            return true
        }

        return false;
    }

    /**
     * @author Xavier Houle
     * Affiche un message d'avertissement si une équipe est assignée plus de 3 fois
     * @param evaluation L'assignation à vérifier
     */
    callbackStandEval = (evaluation: IAssignation) => {
        if (this.verifyIfTeamIsAssignedMoreThan3Times(this.state.standsEval, evaluation.stand_id)) {
            ShowToast("L'équipe a été assignée plus de 3 fois", 5000, "warning", "top-center", false);
        }
    }

    /**
     * @author Xavier Houle
     * Vérifie si une équipe est assignée
     * Si l'équipe est assignée, la fonction modifie le temps, le juge, l'heure d'assignation et le numéro d'équipe
     * Si l'équipe n'est pas déjà assignée, la fonction ajoute une assignation d'équipe
     * Vérifie aussi si l'équipe est déjà assignée plus de 3 fois
     * @param evaluation L'assignation à changer
     */
    handleChangeAssignation = (evaluation: IAssignation) => {
        const existingAssignationIndex = this.state.standsEval.findIndex(assignation => assignation.id === evaluation.id);

        if (existingAssignationIndex !== -1) {
            const updatedStandsEval = [...this.state.standsEval];
            updatedStandsEval[existingAssignationIndex] = evaluation;

            this.setState({ standsEval: updatedStandsEval }, () => {
                this.UpdateSurvey(evaluation.id, evaluation.stand_id, evaluation.survey_id);

                this.callbackStandEval(evaluation);
            });
        } else {
            this.AddSurvey(evaluation.judge_id, evaluation.stand_id, evaluation.survey_id, evaluation.hour)
                .then(newId => {
                    if (newId) {
                        evaluation.id = newId;

                        this.setState(prevState => ({
                            standsEval: [...prevState.standsEval, evaluation]
                        }), () => {
                            this.callbackStandEval(evaluation);
                        });
                    }
                });
        }
    }

    /**
     * @author Xavier Houle
     * @param id L'id de l'assignation a supprimer
     * Supprime l'assignation dans la base de données et update le state de la page
     */
    handleDeleteAssignation = (id: number) => {
        const updatedStandsEval = this.state.standsEval.filter(assignation => assignation.id !== id);

        this.setState({ standsEval: updatedStandsEval });
    }

    /**
     * @author Xavier Houle
     * @param startHour L'heure de départ de la plage horraire
     * @param interval L'intervalle entre les heures de passages
     * S'occupe de changer le state de la page lorsqu'un heure de départ
     * et une intervale est changer. Ensuite elle s'occupe de calculer automatiquement
     * chaque heure de passages.
     */
    handleHoursChange = (startHour: Date, interval: number) => {
        const newHours = this.state.hours.map((element, index) => {
            let newTime = new Date(startHour);

            if (index !== 0) {
                newTime.setMinutes(newTime.getMinutes() + (interval * index));
            }

            return { ...element, time: newTime };
        })

        this.setState({ hours: newHours });
    }

    /**
     * @author Alexis Boivin
     * @param heureDepart L'heure de départ de la plage horraire
     * @param interval L'intervalle entre les heures de passages
     * S'occupe d'ajouter un time_slot.
     */
    handleAddTimeSlot = async (heureDepart : Date, interval: number) => {
    
        let nouveauSlot: TimeSlots;
    
        if (this.state.hours.length <= 0) {
          nouveauSlot = {
            id: 0,
            time: heureDepart
          };
        } 
        else {
          const dernierTimeSlot = this.state.hours[this.state.hours.length - 1];
          const dernierHeure = new Date(dernierTimeSlot.time);
                     
          // Calcul de la prochaine heure
          const prochainHeure = new Date(dernierHeure.getTime() + interval * 60 * 1000);
          nouveauSlot = {
            id: 0,
            time: prochainHeure
          };
        }
      
        try {
          // Appeler le service pour ajouter la nouvelle plage horaire
          if(await JugeStandService.AddTimeSlot(nouveauSlot)){
            this.setState((prevState) => ({
              hours: [...prevState.hours, nouveauSlot],
               
            }));
    
          }
        } catch (error) {
          ShowToast("Une erreur est survenue lors de l'ajout de la plage horaire.", 5000, "error", "top-center", false);
        }
                    
      }
      
    /**
     * @author Alexis Boivin
     * Supprime une plage horaire.
     * S'occupe de supprimer le dernier time_slot qui a été inséré dans la base de donnée.
     */
      handleDeleteTimeSlot = async () => {
        try {
          // Appeler le service pour ajouter la nouvelle plage horaire
          if(await JugeStandService.DeleteTimeSlot()){
            this.setState((prevState) => ({
              hours: prevState.hours.slice(0, -1)
            }));
          }
        } catch (error) {
          ShowToast("Une erreur est survenue lors de l'ajout de la plage horaire.", 5000, "error", "top-center", false);
        }
      }



    /**
     * @author Xavier Houle
     * @returns Les juges groupés par catégorie
     * S'occupe de grouper les juges selon leurs catégories et retourne
     * un tableau avec les juges groupées
     */
    groupJudgesByCategory(): { categoryId: number; judges: JugeInfo[] }[] {
        const groupedJudges: { [key: number]: JugeInfo[] } = {};

        this.state.juges.forEach((juge) => {
            if (!groupedJudges[juge.categories_id]) {
                groupedJudges[juge.categories_id] = [];
            }

            groupedJudges[juge.categories_id].push(juge);
        });

        const groupedJudgesArray = Object.keys(groupedJudges).map((categoryId) => ({
            categoryId: parseInt(categoryId),
            judges: groupedJudges[parseInt(categoryId)],
        }));

        return groupedJudgesArray;
    }

    /**
     * @author Xavier Houle
     * Génère le tableau d'assignation des équipes
     * Les rangées sont les juges qui sont groupés par catégories
     * Les colonnes sont les heures de passages
     * Pour StandRow voir la classe dans /front/components/juge-stand/stand-row.tsx
     * @returns Le tableau d'assignation des équipes
     */
    generateRow(): any {
        return this.groupJudgesByCategory().map((group) => {
            return (
                <React.Fragment key={group.categoryId}>
                    <tr>
                        <td>
                            <h5 style={{ textAlign: "left", marginLeft: "1em" }}>
                                {this.state.categories.find((categorie: Category) => categorie.id === group.categoryId)?.name}
                            </h5>
                            <Divider />
                        </td>
                    </tr>
                    {group.judges.map((juge, index) => (
                        <StandRow
                            key={index}
                            handleChangeAssignation={this.handleChangeAssignation}
                            handleDeleteAssignation={this.handleDeleteAssignation}
                            verifyIfTeamIsAssignedMoreThan3Times={this.verifyIfTeamIsAssignedMoreThan3Times}
                            stands={this.state.stands}
                            leJuge={juge}
                            standsEval={this.state.standsEval}
                            nbreColonnes={this.state.hours.length}
                        ></StandRow>
                    ))}
                </React.Fragment>
            )
        });
    }

    /**
     * @author Xavier Houle
     * Change l'heure de passage pour modifier le state
     * @param newValue La nouvelle heure
     * @param hourNumber L'index du nombre dans le tableau
     */
    onChangeHour = (newValue: Date, hourNumber: number) => {
        let updatedHour = [...this.state.hours];
        updatedHour[hourNumber].time = new Date(newValue);

        this.setState({
            hours: updatedHour
        })
    }

    render() {
        return (
            <>
                <h1 style={{ width: "100%", textAlign: "center" }}>Tableau d'assignation des évaluations</h1>
                <h4 style={{ width: "100%", textAlign: "center" }}>Attention les choix en jaunes sont des équipes qui ne correspondent pas à la catégorie du juge</h4>
                <div style={{ width: "100%", textAlign: "center" }}>
                    <ColorButton variant='contained' style={{ textAlign: "center" }} onClick={this.handleClickOpen}>Changer les heures de passages</ColorButton>
                </div>

                <EvalHour
                    hours={this.state.hours}
                    onChangeHour={this.onChangeHour}
                    boolDialog={this.state.boolDialog}
                    handleClose={this.handleClose}
                    handleHoursChange={this.handleHoursChange}
                    onAddTimeSlot={(heureDepart : Date, interval: number) => this.handleAddTimeSlot(heureDepart, interval)}
                    onDeleteTimeSlot={this.handleDeleteTimeSlot}
                />

                <TableContainer style={{ width: "100%" }}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Juges</TableCell>
                                {
                                    this.state.hours.map((element, index) => {
                                        return (
                                            <TableCell key={index} className={"TimePicker"} align='center'>{("0" + element.time.getHours()).slice(-2)}:{("0" + element.time.getMinutes()).slice(-2)}</TableCell>
                                        )
                                    })
                                }
                            </TableRow>
                        </TableHead>
                        <TableBody sx={{ textAlign: "center" }}>
                            {!this.state.boolLoading && this.generateRow()}
                        </TableBody>
                    </Table>
                </TableContainer>
            </>
        )
    }
}