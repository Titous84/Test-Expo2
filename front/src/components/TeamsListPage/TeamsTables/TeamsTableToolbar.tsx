import { useNavigate } from 'react-router';
import { Button, IconButton, Stack, Tooltip } from '@mui/material'
import { Add as AddIcon, Delete as DeleteIcon } from '@mui/icons-material'
import { GridToolbarColumnsButton, GridToolbarContainer, GridToolbarExport, GridToolbarFilterButton, GridToolbarQuickFilter } from '@mui/x-data-grid'

/**
 * Props pour le composant React: TeamsTableToolbar.
 * @property {() => void} deleteSelectedTeams - Méthode passée par le parent qui supprime les équipes sélectionnées dans le tableau.
 */
interface TeamsTableToolbarProps {
    deleteSelectedTeams: () => void;
}

/**
 * Barre d'outils pour le tableau des équipes.
 * @param {TeamsTableToolbarProps} props - Un objet contenant les props reçues par le composant React.
 * @returns {JSX.Element} Le composant React de cette barre d'outils.
 * 
 * @author Antoine Ouellette, Carlos Cordeiro
 */
export default function TeamsTableToolbar({ deleteSelectedTeams }: TeamsTableToolbarProps) {
    const navigate = useNavigate(); // Récupérer la fonction pour naviguer entre les pages.
    
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
                    {/* Bouton Créer une équipe */}
                    <Button
                        variant="contained"
                        startIcon={<AddIcon />}
                        onClick={()=>{
                            navigate('/inscription')
                        }}
                    >
                        Créer équipe
                    </Button>

                    {/* Bouton Supprimer les équipes sélectionnées */}
                    <Tooltip title="Supprimer">
                        <IconButton
                            onClick={deleteSelectedTeams}
                        >
                            <DeleteIcon fontSize="small" color="error" />
                        </IconButton>
                    </Tooltip>
                </Stack>
            </Stack>
        </GridToolbarContainer>
    )
}