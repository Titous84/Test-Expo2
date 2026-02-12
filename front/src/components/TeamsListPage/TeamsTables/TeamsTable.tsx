import { useEffect, useState } from 'react';
import { Button, Dialog, DialogTitle, DialogContent, DialogContentText, DialogActions } from '@mui/material';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import TeamsTableToolbar from './TeamsTableToolbar';
import TemporarySnackbar, { SnackbarMessageType } from '../../TemporarySnackbar/TemporarySnackbar';
import { validateTeamInfos } from '../Validations/ValidationTeams';
import TeamsListService from '../../../api/TeamsList/TeamsListService';
import { ITeam } from '../../../types/TeamsList/ITeam';
import { TEXTS } from '../../../lang/fr';
import ConfirmationDialog from '../../ConfirmationDialog/ConfirmationDialog';

/**
 * Tableau qui affiche la liste des équipes.
 * Affichage: une équipe par ligne et tous les membres dans la même cellule.
 * 
 * @author Carlos Cordeiro
 */
export default function TeamsTable() {
    // Variables d'état
    const [teamsArray, setTeamsArray] = useState<ITeam[]>([])
    const [categories, setCategories] = useState<{ id: number; name: string }[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [selectedTeamsIds, setSelectedTeamsIds] = useState<number[]>([]);
    // Snackbar pour afficher les messages d'erreur ou de succès.
    const [isSnackbarOpen, setIsSnackbarOpen] = useState<boolean>(false) // Pour contrôler si le snackbar est affiché ou non.
    const [snackbarMessage, setSnackbarMessage] = useState<string>("") // Message à afficher dans le snackbar.
    const [snackbarMessageType, setSnackbarMessageType] = useState<SnackbarMessageType>("error") // Type de message à afficher dans le snackbar. Peut être "success", "error", "warning" ou "info".
    // Fenêtre contextuelle de confirmation pour la suppression des équipes sélectionnées.
    const [isConfirmationDialogOpen, setIsConfirmationDialogOpen] = useState(false);

    // Quelles colonnes on veut afficher et sous quel nom.
    const columns: GridColDef<ITeam>[] = [
        {
            field: "team_number",
            headerName: "Numéro d'équipe",
            width: 150,
            editable: false
        },
        {
            field: "title",
            headerName: "Titre",
            width: 200,
            editable: true
        },
        {
            field: "description",
            headerName: "Description",
            width: 250,
            hideable: true,
            editable: true
        },
        {
            field: "year",
            headerName: "Année",
            width: 200,
            editable: true,
            type: "singleSelect",
            valueOptions: [
              { value: "1re année", label: "1re année" },
              { value: "2e année et +", label: "2e année et +" },
            ],
          },
          {
            field: "category",
            headerName: "Catégorie",
            width: 200,
            editable: true,
            type: "singleSelect",
            valueOptions: categories.map((category) => ({
                value: category.name,
                label: category.name,
            })),
        },
        {
            field: "survey",
            headerName: "Type d'évaluation",
            width: 200,
            hideable: true
        },
        {
            field: "teams_activated",
            headerName: "Équipe activée",
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

        { field: "members", headerName: "Membres", width: 200 },
        { field: "contact_person_name", headerName: "Nom de l'enseignant(e)", width: 200 },
        { field: "contact_person_email", headerName: "Adresse courriel de l'enseignant(e)", width: 250, hideable: true },
    ]

    /**
     * Méthode appelée pour mettre à jour une ligne après modification.
     * @param updatedRow La nouvelle ligne modifiée.
     * @returns La ligne mise à jour.
     */
    const processRowUpdate = async (updatedRow: ITeam) => {
        try {
            // Trouver la ligne originale dans l'état local
            const originalRow = teamsArray.find((team) => team.team_id === updatedRow.team_id);
    
            // On s'assure que tous les champs attendus sont présents et on force le type "team"
            const rowToSend = { ...originalRow, ...updatedRow, type: "team" };

            // Vérifier si les données ont changé
            if (JSON.stringify(originalRow) === JSON.stringify(rowToSend)) {
                return rowToSend;
            }

            const validationErrors = validateTeamInfos(rowToSend);
            if (validationErrors.length > 0) {
            validationErrors.forEach(msg => {
                // Afficher un message d'erreur.
                setSnackbarMessage(msg)
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            });
            throw new Error("VALIDATION_FAILED");
            }
    
            // MAJ de l'équipe dans le backend
            const response = await TeamsListService.patchTeamsInfos(rowToSend);
    
            if (response.error) {
                throw new Error(response.error);
            }
    
            // Afficher un message de succès.
            setSnackbarMessage('Équipe mise à jour avec succès')
            setSnackbarMessageType("success")
            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
    
            // MAJ état local
            setTeamsArray((prevTeams) =>
                prevTeams.map((team) => (team.team_id === rowToSend.team_id ? rowToSend : team))
            );
    
            return rowToSend; // Retourner la ligne mise à jour pour que DataGrid mette à jour son état interne
        } catch (error: any) {
            // Afficher le toast seulement si ce n'est pas une erreur de validation
            if (error.message !== "VALIDATION_FAILED") {
                // Afficher un message d'erreur.
                setSnackbarMessage('Erreur lors de la mise à jour de l\'équipe')
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            }
            throw error; // Annuler les modifis dans DataGrid
        }
    };

    /**
     * Méthode appelée lorsque le bouton de suppression dans la barre d'outils est cliqué.
     * Affiche une boîte de dialogue de confirmation.
     */
    const handleDeleteButtonClick = () => {
        if (selectedTeamsIds.length === 0) {
            // Afficher un message d'attention.
            setSnackbarMessage('Aucune équipe n\'a été sélectionnée pour suppression.')
            setSnackbarMessageType("warning")
            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            return;
        }
        setIsConfirmationDialogOpen(true); // Ouvre le dialog de confirmation
    };

    /**
     * Supprime les équipes sélectionnées.
     */
    const deleteSelectedTeams = () => {
        setIsConfirmationDialogOpen(false);
        TeamsListService.deletesTeamsInfos(selectedTeamsIds)
            .then((response) => {
            if (response.error) {
                ShowErrors(response.error)
            } else if (response.data) {
                // Afficher un message de succès.
                setSnackbarMessage('Les équipes ont été supprimées avec succès.')
                setSnackbarMessageType("success")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.

                setTeamsArray((prevTeams) => prevTeams.filter((team) => !selectedTeamsIds.includes(team.team_id)));
            }
        })
        .catch((error) => {
            ShowErrors(TEXTS.api.errors.communicationFailed)
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
            // Afficher un message d'erreur.
            setSnackbarMessage(message)
            setSnackbarMessageType("error")
            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
        })
    }

    /**
     * Exécute lorsque le composant est monté.
     */
    useEffect(() => {
        const fetchData = async () => {
            try {
                const teamsResponse = await TeamsListService.tryGetTeamsMembersConcats();
                if (teamsResponse.error) {
                    // Afficher un message d'erreur.
                    setSnackbarMessage(teamsResponse.error)
                    setSnackbarMessageType("error")
                    setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                } else {
                    setTeamsArray(teamsResponse.data || []); // Définit un tableau vide si les données sont undefined
                }
    
                const categoriesResponse = await TeamsListService.tryGetCategories();
                if (categoriesResponse.error) {
                    // Afficher un message d'erreur.
                    setSnackbarMessage(categoriesResponse.error)
                    setSnackbarMessageType("error")
                    setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                } else {
                    setCategories(categoriesResponse.data || []); // Définit un tableau vide si les données sont undefined
                }
            } catch (error) {
                // Afficher un message d'erreur.
                setSnackbarMessage("Une erreur est survenue lors de la récupération des données.")
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            } finally {
                setLoading(false);
            }
        };
    
        fetchData();
    }, []); // Le tableau vide [] signifie que cet effet s'exécute uniquement au montage.

    // Retourne le tableau des équipes avec le dialog de confirmation de suppression.
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
                content={"Êtes-vous sûr de vouloir supprimer les équipes sélectionnées?"} // Passer un contenu à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonText={"Supprimer"} // Passer le texte dans bouton de confirmation à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonOnClick={deleteSelectedTeams} // Passer une référence de la méthode de suppression pour que l'enfant puisse la déclencher.
            />

            {/* Le tableau */}
            <div className="table_equipes_concatenes">
                <DataGrid<ITeam>
                    rows={teamsArray}
                    getRowId={(row) => row.team_id}
                    columns={columns}
                    checkboxSelection
                    disableRowSelectionOnClick
                    processRowUpdate={processRowUpdate} // Gère les modifications des cellules
                    onProcessRowUpdateError={(error) => {
                        if (error?.message !== "VALIDATION_FAILED") {
                            // Afficher un message d'erreur.
                            setSnackbarMessage('Erreur lors de la mise à jour de la ligne')
                            setSnackbarMessageType('error')
                            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                        }
                    }}
                    sx={{ width: '100%', minHeight: 400 }} // Le tableau prend 100% de la largeur et une hauteur minimale de 400px.
                    slots={{
                        // Passer <TeamsTableToolbar /> comme barre d'outils pour ce tableau.
                        toolbar: (props) => // `toolbar` fourni un `props` qui contient des données comme les rangées du tableau sélectionnées, etc.
                            <TeamsTableToolbar
                                {...props}
                                deleteSelectedTeams={handleDeleteButtonClick} // Passe la méthode pour supprimer les équipes par ids au composant.
                            />
                    }}
                    onRowSelectionModelChange={(newSelection) => {
                        setSelectedTeamsIds(newSelection.map(id => Number(id)));
                    }}
                />
            </div>
        </>
    )
}