import React, { useEffect, useState } from 'react'
import { Button, IconButton, Stack, Tooltip } from '@mui/material'
import { GridToolbarColumnsButton, GridToolbarContainer, GridToolbarExport, GridToolbarFilterButton, GridToolbarQuickFilter } from '@mui/x-data-grid'
import { Add as AddIcon, Delete as DeleteIcon } from '@mui/icons-material'
import AddNewMember from './AddNewMember';
import TeamsListService from '../../../api/TeamsList/TeamsListService';
import { ITeam } from '../../../types/TeamsList/ITeam';
import { ITeamsMember } from '../../../types/TeamsList/ITeamsMember';

/**
 * Props pour le composant React: TeamsTableToolbar.
 * @property {() => void} deleteSelectedMembers - Méthode passée par le parent qui supprime les membres sélectionnés dans le tableau.
 */
interface TeamsTableToolbarProps {
    deleteSelectedMembers: () => void;
}

/**
 * Barre d'outils pour le tableau des membres de toutes les équipes.
 * @returns {JSX.Element} Le composant React de cette barre d'outils.
 * 
 * @author Antoine Ouellette, Carlos Cordeiro
 */
export default function AllTeamsMembersTableToolbar({ deleteSelectedMembers }: TeamsTableToolbarProps) {
    const [openDialog, setOpenDialog] = React.useState(false); // État pour savoir si le pop-up est ouvert ou non.
    const [equipes, setEquipes] = useState<{ id: number; name: string }[]>([]);

    const handleOpenDialog = () => setOpenDialog(true);
    const handleCloseDialog = () => setOpenDialog(false);

    // Fonction pour récupérer les équipes depuis le backend
    const fetchEquipes = async () => {
        const response = await TeamsListService.tryGetTeamsMembersConcats(); // Appel au service pour récupérer les équipes
        if (response && response.data) {
            // Transformer les données pour correspondre au format attendu
            const transformedEquipes = response.data.map((team: ITeam) => ({
                id: team.team_id,
                name: team.title,
            }));
            setEquipes(transformedEquipes); // Mettre à jour l'état avec les équipes transformées
        } else {
            setEquipes([]); // Réinitialiser l'état si la récupération échoue
        }
    };

    // Utiliser useEffect pour récupérer les équipes au chargement du composant
    useEffect(() => {
        fetchEquipes();
    }, []);

    const handleMemberCreated = async (newMember: ITeamsMember) => {
        const response = await TeamsListService.postTeamsMembers(newMember); // Appel au service pour ajouter un membre
    };

    return (
    <>
        {/* Barre d'outils */}
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
                    {/* Bouton Créer un membre */}
                    <Button
                        variant="contained"
                        startIcon={<AddIcon />}
                        onClick={handleOpenDialog}
                    >
                        Créer membre
                    </Button>

                    {/* Bouton Supprimer les membres sélectionnées */}
                    <Tooltip title="Supprimer">
                        <IconButton
                            onClick={deleteSelectedMembers}
                        >
                            <DeleteIcon fontSize="small" color="error" />
                        </IconButton>
                    </Tooltip>
                </Stack>
            </Stack>
        </GridToolbarContainer>

        {/* Composant AddNewMember */}
        <AddNewMember
            open={openDialog} // Contrôle l'ouverture du pop-up
            onClose={handleCloseDialog} // Ferme le pop-up
            onMemberCreated={handleMemberCreated} // Callback pour gérer la création d'un membre
            equipes={equipes} // Équipes dispos
        />
    </>
    )
}