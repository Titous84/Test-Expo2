import { Button, IconButton, Stack, Tooltip } from '@mui/material'
import { Add as AddIcon, Delete as DeleteIcon } from '@mui/icons-material'
import { GridToolbarColumnsButton, GridToolbarContainer, GridToolbarFilterButton, GridToolbarQuickFilter } from '@mui/x-data-grid'

/**
 * Props pour le composant React: AdministratorsTableToolbar.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsCreationDialogOpen - Méthode passée par le parent qui ouvre la fenêtre contextuelle de création d'un nouvel administrateur.
 * @property {() => void} deleteSelectedAdministrators - Méthode passée par le parent qui supprime les administrateurs sélectionnés dans le tableau.
 */
interface AdministratorsTableToolbarProps {
    parentSetIsCreationDialogOpen: React.Dispatch<React.SetStateAction<boolean>>;
    deleteSelectedAdministrators: () => void;
    isDeleteLoading: boolean;
}

/**
 * Barre d'outils pour le tableau des administrateurs.
 * @prop {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsDialogOpen - Méthode passée par le parent qui ouvre la fenêtre contextuelle de création d'un nouvel administrateur.
 * @prop {() => void} deleteSelectedAdministrators - Méthode passée par le parent qui supprime les administrateurs sélectionnés dans le tableau.
 * @returns {React.Component} Le composant React de cette barre d'outils.
 * 
 * @author Antoine Ouellette
 */
export default function AdministratorsTableToolbar(props: AdministratorsTableToolbarProps) {
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
                </Stack>

                {/* Éléments alignés à la droite de la barre d'outils */}
                <Stack direction="row">
                    {/* Bouton pour créer un nouvel administrateur */}
                    <Button
                        variant="contained"
                        startIcon={<AddIcon />}
                        onClick={()=>{
                            props.parentSetIsCreationDialogOpen(true) // Ouvre la fenêtre contextuelle de création d'un nouvel administrateur.
                        }}
                    >
                        Créer
                    </Button>

                    {/* Bouton pour supprimer les administrateurs sélectionnées dans le tableau */}
                    <Tooltip title="Supprimer">
                        <IconButton
                            loading={props.isDeleteLoading} // Affiche une rétroaction de chargement si la suppression est en cours.
                            color="error"
                            onClick={props.deleteSelectedAdministrators} // Déclenche la méthode dans le tableau parent pour supprimer les administrateurs sélectionnés dans ce tableau.
                        >
                            <DeleteIcon fontSize="small"/>
                        </IconButton>
                    </Tooltip>
                </Stack>
            </Stack>
        </GridToolbarContainer>
    )
}