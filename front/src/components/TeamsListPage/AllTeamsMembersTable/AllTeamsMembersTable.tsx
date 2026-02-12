import { useEffect, useState } from 'react';
import { Button, Dialog, DialogTitle, DialogContent, DialogContentText, DialogActions } from '@mui/material';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import AllTeamsMembersTableToolbar from './AllTeamsMembersTableToolbar';
import TemporarySnackbar, { SnackbarMessageType } from '../../TemporarySnackbar/TemporarySnackbar';
import { validateMemberInfos } from '../Validations/ValidationMembers';
import { ITeamsMember } from '../../../types/TeamsList/ITeamsMember';
import { ITeam } from '../../../types/TeamsList/ITeam';
import TeamsListService from '../../../api/TeamsList/TeamsListService';
import { TEXTS } from '../../../lang/fr';
import ConfirmationDialog from '../../ConfirmationDialog/ConfirmationDialog';

/**
 * Tableau qui affiche la liste des membres de toutes les équipes.
 * Affichage: un membre par ligne et le nom de son équipe.
 * 
 * @returns {JSX.Element} Le composant React de ce tableau
 * 
 * @author Carlos Cordeiro
 */
export default function AllTeamsMembersTable() {
    // Variables d'état
    const [teamsMembersArray, setTeamsMembersArray] = useState<ITeamsMember[]>([])
    const [equipes, setEquipes] = useState<{ id: number; name: string }[]>([]);
    const [selectedMembersIds, setSelectedMembersIds] = useState<number[]>([]);
    // Snackbar pour afficher les messages d'erreur ou de succès.
    const [isSnackbarOpen, setIsSnackbarOpen] = useState<boolean>(false) // Pour contrôler si le snackbar est affiché ou non.
    const [snackbarMessage, setSnackbarMessage] = useState<string>("") // Message à afficher dans le snackbar.
    const [snackbarMessageType, setSnackbarMessageType] = useState<SnackbarMessageType>("error") // Type de message à afficher dans le snackbar. Peut être "success", "error", "warning" ou "info".
    // Fenêtre contextuelle de confirmation pour la suppression des équipes sélectionnées.
    const [isConfirmationDialogOpen, setIsConfirmationDialogOpen] = useState(false);

    // Défini les colonnes du tableau.
    const tableColumns: GridColDef[] = [
        {
            field: 'numero_da',
            headerName: 'Numéro de DA',
            width: 150,
            editable: true,
            renderEditCell: (params) => (
                <input
                    type="text"
                    value={params.value || ""}
                    onChange={e => {
                        // N'accepte que les chiffres
                        const onlyDigits = e.target.value.replace(/\D/g, "");
                        params.api.setEditCellValue({ id: params.id, field: params.field, value: onlyDigits });
                    }}
                    inputMode="numeric"
                    pattern="[0-9]*"
                    style={{ width: "100%" }}
                    autoFocus
                />
            ),
        },
        { field: 'first_name', headerName: 'Prénom', width: 150, editable: true },
        { field: 'last_name', headerName: 'Nom', width: 150, editable: true },
        { 
            field: 'picture_consent',
            headerName: 'Consentement photo',
            width: 150,
            hideable: true,
            editable: true,
            type: "singleSelect",
            valueOptions: [
                { value: 1, label: "Oui" },
                { value: 0, label: "Non" },
            ],
            renderCell: (params) => {
                return <span>{params.value === 1 ? "Oui" : params.value === 0 ? "Non" : "Non spécifié"}</span>;
            },
        },
        {
            field: 'title',
            headerName: 'Nom de l\'équipe',
            width: 200,
            hideable: true,
            editable: true,
            type: "singleSelect",
            valueOptions: equipes.map((teams) => ({
                value: teams.name,
                label: teams.name,
            })),
        }
    ]

    /**
     * Méthode appelée pour mettre à jour une ligne après modification.
     * @param updatedRow La nouvelle ligne modifiée.
     * @returns La ligne mise à jour.
     */
    const processRowUpdate = async (updatedRow: ITeamsMember & { title?: string}) => {
        try {
            // Trouver la ligne originale dans l'état local
            const originalRow = teamsMembersArray.find((member) => member.id === updatedRow.id);

            // Vérifier si les données ont changé
            if (JSON.stringify(originalRow) === JSON.stringify(updatedRow)) {
                // Si aucune modification, retourner la ligne sans effectuer de requête
                return updatedRow;
            }

            // Validation frontend
            const validationErrors = validateMemberInfos(updatedRow);
            if (validationErrors.length > 0) {
                validationErrors.forEach(msg => {
                    // Afficher un message d'erreur.
                    setSnackbarMessage(msg)
                    setSnackbarMessageType("error")
                    setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                });
                throw new Error("VALIDATION_FAILED");
            }

            // Si le champ "title" (équipe) a changé, mapper le nom de l'équipe à son ID
            const selectedTeam = equipes.find((team) => team.name === updatedRow.title);
            if (selectedTeam) {
                updatedRow.team_id = selectedTeam.id; // Mapper le nom de l'équipe à son ID
            } else {
                throw new Error("Équipe sélectionnée introuvable.");
            }

            // Mise à jour du membre dans le backend
            const response = await TeamsListService.patchTeamsMembers(updatedRow);

            if (response.error) {
                throw new Error(response.error);
            }

            // Afficher un message de succès.
            setSnackbarMessage('Membre mis à jour avec succès')
            setSnackbarMessageType("success")
            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.

            // Mise à jour de l'état local
            setTeamsMembersArray((prevMembers) =>
                prevMembers.map((member) => (member.id === updatedRow.id ? updatedRow : member))
            );

            return updatedRow; // Retourner la ligne mise à jour pour que DataGrid mette à jour son état interne
        } catch (error: any) {
            // Afficher le snackbar seulement si ce n'est pas une erreur de validation
            if (error.message !== "VALIDATION_FAILED") {
                // Afficher un message d'erreur.
                setSnackbarMessage('Erreur lors de la mise à jour du membre')
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            }
            throw error;
        }
    };

    /**
     * Méthode appelée lorsque le bouton de suppression dans la barre d'outils est cliqué.
     * Affiche une boîte de dialogue de confirmation.
     */
    const handleDeleteButtonClick = () => {
        if (selectedMembersIds.length === 0) {
            // Afficher un message d'attention.
            setSnackbarMessage("Aucun membre n'a été sélectionné pour la suppression.")
            setSnackbarMessageType("warning")
            setIsSnackbarOpen(true)
            return;
        }
        setIsConfirmationDialogOpen(true); // Ouvre le dialog de confirmation
    };

    /**
     * Supprime les membres sélectionnés.
     */
    const deleteSelectedMembers = () => {
        setIsConfirmationDialogOpen(false);
        TeamsListService.deletesTeamsMembers(selectedMembersIds)
            .then((response) => {
                if (response.error) {
                    ShowErrors(response.error)
                } else if (response.data) {
                    // Affiche un message de succès.
                    setSnackbarMessage('Les membres ont été supprimés avec succès.')
                    setSnackbarMessageType("success")
                    setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.

                    setTeamsMembersArray((prevTeamsMember) => prevTeamsMember.filter((member) => !selectedMembersIds.includes(member.id)));
                }
            })
            .catch((error) => {
                if (
                    error?.message === TEXTS.api.errors.communicationFailed ||
                    error === TEXTS.api.errors.communicationFailed
                ) {
                    ShowErrors(TEXTS.api.errors.communicationFailed);
                } else {
                    // Affiche un message d'erreur.
                    setSnackbarMessage("Erreur lors de la suppression du membre")
                    setSnackbarMessageType("error")
                    setIsSnackbarOpen(true)
                }
            });
    };

    function ShowErrors(errorMessage: string) {
        let errors;

        if (errorMessage === TEXTS.api.errors.communicationFailed) {
            errors = Array(errorMessage)
        } else {
            errors = Object.values(errorMessage)
        }
        
        errors.forEach(message => {
            setSnackbarMessage(message);
            setSnackbarMessageType("error");
            setIsSnackbarOpen(true);
        });
    }

    /**
     * Exécute lorsque le composant est monté.
     */
    useEffect(() => {
        const fetchTeamsMembers = async () => {
            try {
                // Récupère la liste des membres de toutes les équipes.
                const response = await TeamsListService.tryGetTeamsMembers();
                if (response.error) {
                    ShowErrors(response.error)
                } else if (response.data) {
                    setTeamsMembersArray(response.data); // Met à jour les données du tableau.
                }

                const teamsNamesResponse = await TeamsListService.tryGetTeamsMembersConcats();
                if (teamsNamesResponse.error) {
                    // Affiche un message d'erreur.
                    setSnackbarMessage(teamsNamesResponse.error);
                    setSnackbarMessageType("error");
                    setIsSnackbarOpen(true);
                } else {
                    // Conversion des données de l'API en un tableau d'objets avec id et name
                    const transformedTeams = (teamsNamesResponse.data || []).map((team: ITeam) => ({
                        id: team.team_id,
                        name: team.title,
                    }));

                    setEquipes(transformedTeams); // Définit les données transformées
                }
            } catch (error) {
                ShowErrors('Une erreur est survenue lors de la récupération des membres des équipes');
            }
        };

        fetchTeamsMembers();
    }, []) // Le tableau vide [] signifie que cet effet s'exécute uniquement au montage.

    // Retourne le tableau des membres des équipes et le dialog de confirmation.
    return (
        <>
            {/* Snackbar caché par défaut qui affiche les messages */}
            <TemporarySnackbar
                parentIsSnackbarOpen={isSnackbarOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si le snackbar doit être affiché.
                parentSetIsSnackbarOpen={setIsSnackbarOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                message={snackbarMessage} // Passer un message à afficher dans le snackbar.
                snackbarMessageType={snackbarMessageType} // Passer le type de message pour changer la couleur du snackbar.
            />

            {/* Fenêtre contextuelle de confirmation cachée par défaut */}
            <ConfirmationDialog
                parentIsDialogOpen={isConfirmationDialogOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si a fenêtre contextuelle de confirmation doit être affichée.
                parentSetIsDialogOpen={setIsConfirmationDialogOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                title={"Confirmation de suppression"} // Passer un titre à afficher dans la fenêtre contextuelle de confirmation.
                content={"Êtes-vous sûr de vouloir supprimer les membres sélectionnés?"} // Passer un contenu à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonText={"Supprimer"} // Passer le texte dans bouton de confirmation à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonOnClick={deleteSelectedMembers} // Passer une référence de la méthode de suppression pour que l'enfant puisse la déclencher.
            />

            <DataGrid<ITeamsMember>
                rows={teamsMembersArray}
                columns={tableColumns}
                checkboxSelection
                disableRowSelectionOnClick
                processRowUpdate={processRowUpdate} // Appelle la méthode pour mettre à jour une ligne après modification.
                onProcessRowUpdateError={(error) => {
                    if (error?.message !== "VALIDATION_FAILED") {
                        // Affiche un message d'erreur.
                        setSnackbarMessage('Erreur lors de la mise à jour de la ligne')
                        setSnackbarMessageType("error")
                        setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                    }
                }}
                sx={{ width: '100%', minHeight: 400 }} // Le tableau prend 100% de la largeur et une hauteur minimale de 400px.
                slots={{
                    // Passer <AllTeamsMembersToolbar /> comme barre d'outils pour ce tableau.
                    toolbar: (props) => // `toolbar` fourni un `props` qui contient des données comme les rangées du tableau sélectionnées, etc.
                        <AllTeamsMembersTableToolbar
                            {...props}
                            deleteSelectedMembers={handleDeleteButtonClick} // Passe la méthode pour supprimer les membres par IDs au composant.
                        />
                }}
                onRowSelectionModelChange={(newSelection) => {
                    setSelectedMembersIds(newSelection.map((id) => Number(id)));
                }}
            />
        </>
    )
}