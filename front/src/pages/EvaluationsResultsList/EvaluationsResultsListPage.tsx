import React from 'react';
import { Typography, Box } from '@mui/material';
import { useNavigate } from 'react-router-dom';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import { Button } from '@mui/material';
import { TEXTS } from '../../lang/fr';
import styles from './EvaluationsResultsListPage.module.css';
import { ShowToast } from '../../utils/utils';
import ResultService from '../../api/result/resultService';
import ResultInfo from '../../types/results/resultInfo';
import EvaluationResultsToolbar from './EvaluationResultsTableToolBar';

/**
 * NavigateButtonProps est une interface pour les propriétés du composant NavigateButton.
 * 
 * @property {EnhancedResultInfo} teamDetails Détails de l'équipe pour laquelle le bouton de navigation est affiché.
 * 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 */
interface NavigateButtonProps {
    teamDetails: EnhancedResultInfo;
}

/**
 * JudgeExclusion est une interface pour les informations d'exclusion d'un score de juge.
 * 
 * @property {string} teamName Nom de l'équipe concernée.
 * @property {string} judgeName Nom du juge concerné.
 * @property {boolean} isExcluded Indique si le score du juge est exclu ou non.
 * 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 */
interface JudgeExclusion {
    teamName: string;
    judgeName: string;
    isExcluded: boolean;
}

interface ResultRowData {
    categorie: string;
    teams_name: string;
    first_name_user: string;
    last_name_user: string;
    judge_id: number;
    comments: string;
    global_score: number;
    finalScore: number;
    judgeScores: {
        score: number;
        judgeName: string;
        isChecked: boolean;
        comments: string;
        judge_id: number;
    }[];
    id: number;
}

/**
 * Interface qui définit la structure des informations de résultat après traitement et calcul. 
 * 
 * @extends ResultInfo Informations de base du résultat.
 * @property {number | null} finalScore Score final calculé de l'équipe, peut être null si non calculé.
 * @property {{score: number; judgeName: string; isChecked: boolean}[]} judgeScores Liste des scores attribués par les juges, incluant : le nom du juge et si le score est sélectionné pour le calcul final.
 * 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 */
export interface EnhancedResultInfo extends ResultInfo {
    finalScore?: number | null;
    judgeScores: {
        score: number;
        judgeName: string;
        isChecked: boolean;
        comments?: string;
        judge_id: number;  // ID de l'évaluation pour chaque score de juge
    }[];
}

/**
 * Définit l'état du composant ResultsList.
 * 
 * @property {EnhancedResultInfo[]} results Tableau contenant les résultats détaillés de chaque équipe.
 * @property {Object} excludedScores Dictionnaire stockant l'état d'exclusion des scores pour chaque juge et chaque équipe.
 * @property {number} resetKey Clé pour réinitialiser les tris du tableau.
 * 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 * 
 */
interface EvaluationsResultsListPageState {
    results: EnhancedResultInfo[];
    excludedScores: { [teamName: string]: { [judgeName: string]: boolean } };
    resetKey: number; // Clé pour réinitialiser les tri du tableau 
    selectedRows: number[];
    selectedJudgeScores: { teamName: string, judgeId: number }[];
}

/**
 * Composant NavigateButton pour le bouton de navigation vers les détails de l'équipe.
 * 
 * @param {NavigateButtonProps} props Propriétés passées au composant.
 * @property {EnhancedResultInfo} teamDetails Détails de l'équipe pour laquelle le bouton de navigation est affiché.
 * @returns {React.FC<NavigateButtonProps>} Composant NavigateButton pour le bouton de navigation vers les détails de l'équipe.
 * 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 */
const NavigateButton: React.FC<NavigateButtonProps> = ({ teamDetails }) => {
    const navigate = useNavigate();

    return (
        <Button
            variant="contained"
            color="secondary"
            className="resetButton"
            onClick={() => navigate(`/details-equipe/${teamDetails.teams_name}`, { state: { teamDetails } })}
        >
            Détails
        </Button>
    );
};


/**
 * Page des résultats des évaluations des équipes par les juges.
 * 
 * Initialise l'état du composant avec les résultats et les notes exclues.
 * @param {Object} props Propriétés passées au composant.
 * 
 * @author Souleymane Soumaré
 * @editor Francis Payan 
 */
class EvaluationsResultsListPage extends React.Component<{}, EvaluationsResultsListPageState> {
    constructor(props: {}) {
        super(props);
        this.state = {
            results: [],
            excludedScores: {},
            resetKey: 0,
            selectedRows: [],
            selectedJudgeScores: [],
        };
    }

    /**
     * Appelle les fonctions pour récupérer les informations des résultats et les états d'exclusion des notes des juges.
     *
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    async componentDidMount() {
        await this.getInfos(); // Récupère les infos existantes
        await this.fetchJudgeScoreExclusions(); // Fonction pour récupérer l'état d'exclusion des notes des juges
    }

    /**
     * Transforme les données d'exclusion des notes des juges en un format plus facile à gérer.
     * 
     * @param {JudgeExclusion[]} exclusions Les données d'exclusion des scores des juges à transformer.
     * @returns {Object} Un objet contenant les états d'exclusion des scores pour chaque juge et chaque équipe.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    mapExclusionsToState(exclusions: JudgeExclusion[]): { [teamName: string]: { [judgeName: string]: boolean } } {
        const exclusionState: { [teamName: string]: { [judgeName: string]: boolean } } = {};
        exclusions.forEach(exclusion => { // Pour chaque exclusion de note de juge 
            if (!exclusionState[exclusion.teamName]) { // Si l'équipe n'a pas encore été traitée 
                exclusionState[exclusion.teamName] = {}; // Crée un objet pour stocker les exclusions de note de juge pour l'équipe
            }
            exclusionState[exclusion.teamName][exclusion.judgeName] = exclusion.isExcluded; // Stocke l'état d'exclusion de la note de juge pour l'équipe
        });
        return exclusionState; // Retourne l'état d'exclusion des notes des juges pour chaque équipe 
    }

    /**
     * Récupère les données d'exclusion des notes des juges depuis le service API.
     * 
     * @returns {Promise<void>} Une promesse qui résout une fois les données d'exclusion sont récupérées.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    async fetchJudgeScoreExclusions() {
        const exclusionsResponse = await ResultService.getScoreExclusions(); // Appel API pour récupérer les exclusions de notes des juges 
        if (exclusionsResponse.data) { // Si l'appel API est un succès et que les données sont retournées
            const exclusionsData = exclusionsResponse.data; // Récupère les données d'exclusion des notes des juges
            const exclusions: JudgeExclusion[] = this.transformExclusions(exclusionsData); // Transforme les données d'exclusion des notes des juges
            this.setState({ excludedScores: this.mapExclusionsToState(exclusions) }); // Met à jour l'état avec les données d'exclusion des notes des juges
        } else if (exclusionsResponse.error) { // Si l'appel API échoue ou ne retourne pas de données
            ShowToast(exclusionsResponse.error, 5000, "warning", "top-center", true); // Affiche un message d'erreur
        }
    }

    /**
     * Transforme les données d'exclusion des notes des juges en un format plus facile à gérer.
     * 
     * @param {Object} data Les données d'exclusion des notes des juges à transformer.
     * @returns {JudgeExclusion[]} Un tableau d'objets JudgeExclusion contenant les états d'exclusion des scores pour chaque juge et chaque équipe.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    transformExclusions(data: { [teamName: string]: { [judgeName: string]: boolean } }): JudgeExclusion[] {
        let exclusions: JudgeExclusion[] = [];
        Object.keys(data).forEach(teamName => {
            Object.keys(data[teamName]).forEach(judgeName => {
                exclusions.push({
                    teamName,
                    judgeName,
                    isExcluded: data[teamName][judgeName]
                });
            });
        });
        return exclusions;
    }

    /**
     * Récupère les résultats de l'évaluation depuis le service API et les traite.
     * 
     * @author Souleymane Soumaré
     * @editor Francis Payan 
     */
    async getInfos() {
        // Récupère les résultats de l'évaluation depuis le service API. 
        const APIResults = await ResultService.GetResult();
        // console.log(JSON.stringify(APIResults.data, null, 2));
        if (APIResults.data) {
            // Regroupement des scores par équipe tout en calculant la note finale
            const enhancedResultsWithoutIds = this.enhanceAndGroupResults(APIResults.data);
            // Ajout d'un id pour chaque résultat. <DataGrid /> a besoin d'un id unique pour chaque ligne du tableau.
            const enhancedResults = enhancedResultsWithoutIds.map((result, index) => ({ ...result, id: index }));
            //console.log(JSON.stringify(enhancedResults, null, 2));
            // Appliquer l'état.
            this.setState({ results: enhancedResults }); // Met à jour l'état avec les résultats traités et calculés 
        } else if (APIResults.data === null) {
            // Affichage d'un message si aucun résultat n'est retourné par l'API
            ShowToast("Aucun résultat à afficher", 5000, "warning", "top-center", false);
        }
    }

    /**
     * Regroupe les résultats par équipe et calcule la note finale pour chaque équipe.
     * 
     * @param {ResultInfo[]} results Les résultats bruts à transformer et à grouper.
     * @returns {EnhancedResultInfo[]} Un tableau d'objets EnhancedResultInfo avec les notes par équipe et la note finale calculée.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    enhanceAndGroupResults(results: ResultInfo[]): EnhancedResultInfo[] {
        const groupedResults: { [key: string]: EnhancedResultInfo } = {}; // cette variable stockera les résultats groupés par équipe

        results.forEach(result => { // pour chaque résultat brut reçu du service API
            const teamName = result.teams_name; // récupère le nom de l'équipe
            const judgeScoreEntry = { // crée un objet pour stocker la note du juge et s'il est inclus dans le calcul final ou non 
                score: result.global_score,
                judgeName: `${result.first_name_user} ${result.last_name_user}`,
                isChecked: false, // initialise à false pour inclure la note dans le calcul final par défaut
                comments: result.comments || "Aucun commentaire",
                judge_id: result.judge_id // Id du juge pour chaque score de juge
            };


            if (!groupedResults[teamName]) { // si l'équipe n'a pas encore été traitée 
                groupedResults[teamName] = { // crée un nouvel objet pour stocker les scores de l'équipe 
                    ...result, // copie les informations de base de l'équipe 
                    finalScore: null, // Initialise finalScore avec null
                    judgeScores: [judgeScoreEntry] // Ajoute la note du juge à la liste des scores de l'équipe 
                };
            } else {
                // Ici, nous nous assurons que judgeScores est un tableau non null avant de faire un push
                groupedResults[teamName].judgeScores = groupedResults[teamName].judgeScores || [];
                groupedResults[teamName].judgeScores.push(judgeScoreEntry); // Ajoute la note du juge à la liste des notes de l'équipe
            }
        });

        /**
         * Calcule la note finale pour chaque équipe en excluant les scores marqués comme non inclus.
         * 
         * @param {EnhancedResultInfo[]} groupedResults Les résultats groupés par équipe.
         * @returns {EnhancedResultInfo[]} Les résultats après modifications avec les notes finales calculées.
         * 
         * @editor Francis Payan
         * Code partiellement généré par ChatGPT et Copilot.
         * @see https://www.chatgpt.com/
         */
        const finalResults: EnhancedResultInfo[] = Object.values(groupedResults).map(teamResult => {
            // Calcule la note finale en excluant les scores qui sont marqués comme non inclus
            const includedScores = teamResult.judgeScores!.filter(js => !js.isChecked);
            const totalScore = includedScores.reduce((acc, curr) => acc + curr.score, 0); // Calcule la somme des notes, acc = accumulateur, curr = valeur actuelle
            const finalScore = includedScores.length > 0 ? Math.round(totalScore / includedScores.length) : null; // Calcule la note finale en arrondissant à l'entier le plus proche 

            return { // Retourne un objet EnhancedResultInfo avec les informations de l'équipe et la note finale calculée 
                ...teamResult, // Copie les informations de l'équipe 
                finalScore, // Stocke la note finale calculée dans l'objet 
            };
        });

        /**
         * Trie les résultats par catégorie puis par note finale décroissante.
         * 
         * @param {EnhancedResultInfo} a Le premier résultat à comparer.
         * @param {EnhancedResultInfo} b Le deuxième résultat à comparer.
         * @returns {number} La valeur de comparaison entre les deux résultats.
         * 
         * @author Francis Payan
         * Code partiellement généré par ChatGPT et Copilot.
         * @see https://www.chatgpt.com/
         */
        finalResults.sort((a, b) => {
            const categoryCompare = a.categorie.localeCompare(b.categorie); // Trie par catégorie 
            if (categoryCompare !== 0) return categoryCompare; // Si les catégories sont différentes, retourne la comparaison des catégories 
            return b.finalScore! - a.finalScore!; // Trie par note finale décroissante 
        });

        return finalResults; // Retourne le tableau des résultats après modification et triés 
    }

    /**
     * Gère l'action d'exclusion d'un score de juge pour une équipe donnée.
     *  
     * @param {React.ChangeEvent<HTMLInputElement>} event L'événement déclencheur qui contient l'état coché de l'élément.
     * @param {string} teamName Le nom de l'équipe concernée par la modification.
     * @param {string} judgeName Le nom du juge concerné par la modification.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    handleScoreExclusion = (event: React.ChangeEvent<HTMLInputElement>, teamName: string, judgeName: string) => {
        const isChecked = event.target.checked; // Récupère l'état coché de l'élément 
        this.setState(prevState => ({ // Met à jour l'état en excluant le score du juge pour l'équipe concernée 
            ...prevState, // Copie l'état actuel 
            excludedScores: { // Met à jour l'état des notes exclues 
                ...prevState.excludedScores, // Copie l'état actuel des notes exclues 
                [teamName]: { // Met à jour l'état des notes exclues pour l'équipe concernée 
                    ...prevState.excludedScores[teamName], // Copie l'état actuel des notes exclues pour l'équipe concernée
                    [judgeName]: isChecked // Met à jour l'état de la note du juge pour l'équipe concernée
                }
            }
        }), () => { // Après la mise à jour de l'état, recalcule les notes finales
            const enhancedResults = this.enhanceAndGroupResults(this.state.results);
            this.setState({ results: enhancedResults }); // Met à jour l'état avec les résultats traités et calculés
        });
    };

    /**
     * Inverse l'état d'inclusion d'un score de juge pour une équipe spécifique.
     * 
     * @param teamName Le nom de l'équipe pour laquelle la note du juge doit être modifié.
     * @param judgeName Le nom du juge dont la note est concerné.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    toggleJudgeScore = async (teamName: string, judgeName: string) => {
        this.setState(prevState => { // Met à jour l'état en inversant l'état d'inclusion de la note du juge pour l'équipe concernée
            const resultsCopy = prevState.results.map(result => { // Copie les résultats actuels
                if (result.teams_name === teamName) { // Si l'équipe correspond à l'équipe concernée
                    const judgeScoresCopy = result.judgeScores.map(judgeScore => { // Copie les notes des juges de l'équipe
                        if (judgeScore.judgeName === judgeName) { // Si le juge correspond au juge concerné
                            const newIsChecked = !judgeScore.isChecked; // Inverse l'état d'inclusion de la note du juge

                            // Appel API avec judge_id pour mettre à jour l'exclusion
                            this.updateJudgeScoreExclusion(judgeScore.judge_id, newIsChecked).catch(error => {
                                console.error("lors de la mise à jour du statut d'exclusion:", error);
                            });
                            return { ...judgeScore, isChecked: newIsChecked }; // Retourne la note du juge avec le nouvel état d'inclusion
                        }
                        return judgeScore; // Retourne la note du juge sans modification si ce n'est pas le juge concerné 
                    });

                    // Calcul de la note finale ajustée après la mise à jour de l'exclusion
                    const includedScores = judgeScoresCopy.filter(js => !js.isChecked); // Filtre les notes des juges incluses dans le calcul final 
                    const totalScore = includedScores.reduce((acc, curr) => acc + curr.score, 0); // Calcule la somme des notes des juges incluses 
                    const finalScore = includedScores.length > 0 ? Math.round(totalScore / includedScores.length) : 0; // Calcule la note finale en arrondissant à l'entier le plus proche 

                    return { ...result, judgeScores: judgeScoresCopy, finalScore }; // Retourne l'équipe avec les notes des juges mises à jour et la note finale recalculée 
                }
                return result; // Retourne l'équipe sans modification si ce n'est pas l'équipe concernée 
            });

            return { ...prevState, results: resultsCopy }; // Retourne l'état avec les résultats mis à jour 
        });
    }

    /**
     * Gère la suppression des notes de juge sélectionnées.	
     * @param isChecked Indique si la note est sélectionnée ou non.
     * @param teamName Le nom de l'équipe sélectionnée.
     * @param judgeId L'identifiant du juge sélectionné.
     * @author Tommy Garneau
     */
    handleJudgeScoreSelection = (isChecked: boolean, teamName: string, judgeId: number) => {
        // Met à jour l'état en supprimant la note sélectionnée
        this.setState((prevState) => {
            const selectedJudgeScores = [...prevState.selectedJudgeScores];

            // Vérifie si la note du juge est déjà sélectionnée
            if (isChecked) {
                // Ajouter la note sélectionnée
                selectedJudgeScores.push({ teamName, judgeId });
            } else {
                // Supprimer la note désélectionnée
                const index = selectedJudgeScores.findIndex(
                    (selected) => selected.teamName === teamName && selected.judgeId === judgeId
                );
                // Supprimer l'index si la note est désélectionnée
                if (index !== -1) {
                    selectedJudgeScores.splice(index, 1);
                }
            }

            return { selectedJudgeScores };
        });
    };

    /**
     * Appelle l'API pour mettre à jour l'exclusion d'une note de juge.
     * 
     * @param judge_id L'identifiant du juge pour lequel le statut doit être mis à jour.
     * @param isExcluded Le nouveau statut d'exclusion de la note.
     * @returns Une promesse qui résout une fois la mise à jour est effectuée.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    async updateJudgeScoreExclusion(judge_id: number, isExcluded: boolean): Promise<void> {
        const updateResponse = await ResultService.updateScoreExclusion(judge_id, isExcluded);
    }

    /**
     * resetSorts réinitialise les tris du tableau MUI.
     * 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    resetSorts = () => {
        // Incrémente resetKey pour forcer la récréation du tableau MUI
        this.setState(prevState => ({ resetKey: prevState.resetKey + 1 }));
    };

    /**
     * Fonction pour afficher une notification de succès.
     * @param message Message à afficher dans la notification.
     */
    ShowSuccess(message: string) {
        ShowToast(message, 5000, "success", "top-center", false)
    }

    /**
     * Fonction pour afficher une notification d'erreur.
     * @param error Message d'erreur à afficher dans la notification.
     */
    ShowErrors(error: string) {
        let errors;
        if (error === TEXTS.api.errors.communicationFailed) {
            errors = Array(error)
        } else {
            errors = Object.values(error)
        }
        errors.forEach(message =>
            ShowToast(message, 5000, "error", "top-center", false)
        )
    }

    /**
     * Rendu du composant ResultsList.
     * 
     * @author Francis Payan
     * @author Tommy Garneau
     * Inspiré du fichier tableTeamInfo.tsx
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    render() {


        // Quelles colonnes on veut afficher et sous quel nom.
        const columns: GridColDef[] = [
            { field: 'categorie', headerName: 'Catégorie', flex: 0.5 },
            { field: 'teams_name', headerName: 'Nom de l\'équipe', flex: 0.5 },
            {
                field: 'judgeScores',
                headerName: 'Note du juge',
                flex: 1,
                renderCell: (params) => {
                    const currentResultRow = params.row as ResultRowData;
                    return (
                        <div className={styles.judgeScoresContainer}>
                            {currentResultRow.judgeScores.map((judgeScore, index) => (
                                <div key={index} className={styles.judgeScoreWrapper}>
                                    {/* Checkbox pour sélectionner/désélectionner la note du juge */}
                                    <input
                                        type="checkbox"
                                        checked={this.state.selectedJudgeScores.some(
                                            // Vérifie si la note du juge est sélectionnée
                                            (selected) =>
                                                selected.teamName === currentResultRow.teams_name &&
                                                selected.judgeId === judgeScore.judge_id
                                        )}
                                        // Appelle la fonction pour gérer la sélection de la note du juge
                                        onChange={(e) =>
                                            this.handleJudgeScoreSelection(
                                                e.target.checked,
                                                currentResultRow.teams_name,
                                                judgeScore.judge_id
                                            )
                                        }
                                    />
                                    <span className={styles.judgeScore}>{`${judgeScore.judgeName} (${judgeScore.score}%)`}</span>
                                </div>
                            ))}
                        </div>
                    );
                }
            },
            { field: 'finalScore', headerName: 'Note finale de l\'équipe (en %)', width: 202 },
            { field: 'commentaires de l\'équipe', headerName: 'Commentaires de l\'équipe', flex: 1 },
        ];

        return (
            <Box sx={{ height: 600, width: '100%' }}>
                <Typography variant="h4" className={styles.title} sx={{ mt: 4, mb: 2, fontWeight: 'bold' }}>{TEXTS.admin.resultats.layout1.link1}</Typography>
                <div className="my-custom-table">
                    <DataGrid
                        rows={this.state.results}
                        columns={columns}
                        rowCount={this.state.results.length}
                        checkboxSelection
                        disableRowSelectionOnClick
                        onRowSelectionModelChange={(newSelection) => {
                            const selectedIds = newSelection.map(id => Number(id));
                            this.setState({ selectedRows: selectedIds });
                        }}
                        sx={{ width: '100%', minHeight: 400 }}
                        slots={{
                            toolbar: () => (
                                <EvaluationResultsToolbar
                                    sendInfo={this.sendInfo}
                                    deleteJudgeScores={this.deleteJudgeScores}
                                    selectedRows={this.state.selectedRows}
                                    selectedJudgeScores={this.state.selectedJudgeScores}
                                    results={this.state.results} // Passer les résultats comme prop
                                />
                            ),
                        }}
                    />

                </div>
            </Box>
        );
    }

    /**
     * Supprime les notes sélectionnées des juges.
     *  
     * @author Tommy Garneau
     */
    deleteJudgeScores = async () => {
        const { selectedJudgeScores } = this.state;

        // Afficher une boîte de dialogue de confirmation
        const confirmation = window.confirm(
            "Êtes-vous sûr de vouloir supprimer les résultats sélectionnés?"
        );
        if (!confirmation) {
            return;
        }

        try {
            const promises = selectedJudgeScores.map(({ teamName, judgeId }) =>
                ResultService.deletesJudgeScore(teamName, judgeId)
            );
            await Promise.all(promises);

            this.ShowSuccess("Les notes sélectionnées ont été supprimées avec succès.");

            // Mettre à jour l'état local après suppression
            this.setState((prevState) => {
                const updatedResults = prevState.results.map((result) => {
                    const updatedJudgeScores = result.judgeScores.filter(
                        (judgeScore) =>
                            !selectedJudgeScores.some(
                                (selected) =>
                                    selected.teamName === result.teams_name &&
                                    selected.judgeId === judgeScore.judge_id
                            )
                    );

                    /**
                     * Recalculer la note finale
                     * Code partiellement généré par ChatGPT.
                     * @see https://www.chatgpt.com/
                     */
                    const includedScores = updatedJudgeScores.filter((js) => !js.isChecked);
                    const totalScore = includedScores.reduce((acc, curr) => acc + curr.score, 0);
                    const finalScore =
                        includedScores.length > 0
                            ? Math.round(totalScore / includedScores.length)
                            : null;

                    return {
                        ...result,
                        judgeScores: updatedJudgeScores,
                        finalScore,
                    };
                });

                return { results: updatedResults, selectedJudgeScores: [] }; // Réinitialiser les sélections
            });
        } catch (error) {
            this.ShowErrors("Une erreur est survenue lors de la suppression des notes sélectionnées.");
        }
    };


    /**
     * Envoyer les informations par mail.
     */
    async sendInfo(result: ResultInfo | undefined) {
        // TODO: Implémenter l'envoi des informations par courriel.
    }
}

export default EvaluationsResultsListPage;