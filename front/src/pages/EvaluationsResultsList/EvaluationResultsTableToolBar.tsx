import { Button, IconButton, Stack, Tooltip } from '@mui/material'
import { Add as AddIcon, Delete as DeleteIcon } from '@mui/icons-material'
import { GridToolbarColumnsButton, GridToolbarContainer, GridToolbarExport, GridToolbarFilterButton, GridToolbarQuickFilter } from '@mui/x-data-grid'
import ResultInfo from '../../types/results/resultInfo';

/**
 * Props pour le composant React: EvaluationResultsToolbar.
 * @property {() => void} deleteSelectedTeams - Méthode passée par le parent qui supprime les résultat sélectionnées dans le tableau.
 */
interface EvaluationResultsToolbarProps {
    sendInfo: (result: ResultInfo | undefined) => void;
    deleteJudgeScores: () => void;
    selectedRows: number[];
    selectedJudgeScores: { teamName: string, judgeId: number }[];
    results: ResultInfo[];
}

/**
 * Barre d'outils pour le tableau des résultats.
 * @param {EvaluationResultsToolbarProps} props - Un objet contenant les props reçues par le composant React.
 * @returns {JSX.Element} Le composant React de cette barre d'outils.
 * 
 * @author Antoine Ouellette, Tommy Garneau
 */
export default function EvaluationResultsToolbar({ sendInfo, deleteJudgeScores, selectedRows, selectedJudgeScores, results }: EvaluationResultsToolbarProps) {
    return (
        <GridToolbarContainer>
            <Stack direction="row" spacing={2} justifyContent="space-between" width="100%">
                {/* Éléments alignés à la gauche de la barre d'outils */}
                <Stack direction="row">
                    {/* Barre de recherche */}
                    <GridToolbarQuickFilter />

                    {/* Bouton Filtres */}
                    <Tooltip title="Filtres">
                        <GridToolbarFilterButton />
                    </Tooltip>

                    {/* Boutons des colonnes affichées */}
                    <Tooltip title="Colonnes">
                        <GridToolbarColumnsButton />
                    </Tooltip>

                    {/* Bouton télécharger en fichier .csv */}
                    <GridToolbarExport
                        slotProps={{
                            tooltip: { title: 'Télécharger' },
                            // button: { variant: 'outlined' }
                        }}
                    />
                </Stack>

                {/* Éléments alignés à la droite de la barre d'outils */}
                <Stack direction="row">
                    {/* Bouton pour envoyer les résultats */}
                    <Button
                        variant="contained"
                        color="secondary"
                        onClick={() =>
                            selectedRows.forEach((id) =>
                                sendInfo(results.find((result) => result.id === id))
                            )
                        }
                        disabled={selectedRows.length === 0} // Désactiver si aucune ligne n'est sélectionnée
                    >
                        Envoyer résultats
                    </Button>

                    {/* Bouton Supprimer les résultats sélectionnées */}
                    <Tooltip title="Supprimer">
                        <IconButton
                            onClick={deleteJudgeScores} // Appelle la méthode pour supprimer les résultats sélectionnés
                            disabled={selectedJudgeScores.length === 0} // Désactiver si aucune note n'est sélectionnée
                        >
                            <DeleteIcon fontSize="small" color="error" />
                        </IconButton>
                    </Tooltip>
                </Stack>
            </Stack>
        </GridToolbarContainer>
    )
}