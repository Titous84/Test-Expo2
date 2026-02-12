import { Button } from "@mui/material";
import { DataGrid, GridColDef } from "@mui/x-data-grid";
import AdministratorCreationDialog from "../AdministratorCreationDialog/AdministratorCreationDialog";
import AdministratorsTableToolbar from "./AdministratorsTableToolbar";
import ConfirmationDialog from "../../ConfirmationDialog/ConfirmationDialog";
import TemporarySnackbar from "../../TemporarySnackbar/TemporarySnackbar";
import useAdministratorsTableHook from "./AdministratorsTableHook";

/**
 * Tableau qui affiche la liste des administrateurs.
 * Les cellules sont modifiables directement dans le tableau.
 */
export default function AdministratorsTable() {
    // *** Variables d'état ***
    const {
        administratorsList,
        setAdministratorsList,
        selectedAdministratorsIds,
        setSelectedAdministratorsIds,
        isTableLoading,
        setIsTableLoading,
        isDeleteLoading,
        setIsDeleteLoading,
        isSnackbarOpen,
        setIsSnackbarOpen,
        snackbarMessage,
        setSnackbarMessage,
        snackbarMessageType,
        setSnackbarMessageType,
        isCreationDialogOpen,
        setIsCreationDialogOpen,
        isConfirmationDialogOpen,
        setIsConfirmationDialogOpen,
        getAllAdministrators,
        handleDeleteButtonClick,
        deleteSelectedAdministrators
    } = useAdministratorsTableHook(); // Récupére les variables d'état et les méthodes dans le fichier séparé.
    
    // Quelles colonnes on veut afficher dans le tableau et sous quel nom.
    const tableColumns: GridColDef[] = [
        {
            field: "email",
            headerName: "Courriel",
            width: 300,
            editable: true
        },
        {
            field: "modify_password",
            headerName: "Modifier mot de passe",
            width: 224,
            renderCell: (params) => {
                return (
                    <Button
                        variant="contained"
                        color="primary"
                        onClick={() => {
                            setSnackbarMessageType("info")
                            setSnackbarMessage("Cette fonctionnalité n'est pas encore disponible.")
                            setIsSnackbarOpen(true)
                        }}
                    >
                        Modifier mot de passe
                    </Button>
                );
            }
        }
    ]

    return (
        <div data-testid="administratorsTable">
            {/* Le tableau */}
            <DataGrid
                rows={administratorsList}
                columns={tableColumns}
                checkboxSelection
                disableRowSelectionOnClick
                loading={isTableLoading} // Affiche une rétroaction de chargement si le tableau est en train de charger.
                sx={{ width: '100%', minHeight: 400 }}
                slots={{
                    // Passer <AdministratorsTableToolbar /> comme barre d'outils pour ce tableau.
                    toolbar: (props) => // `toolbar` fourni un `props` qui contient des données comme les rangées du tableau sélectionnées, etc.
                        <AdministratorsTableToolbar
                            {...props}
                            parentSetIsCreationDialogOpen={setIsCreationDialogOpen} // Passe une référence de la méthode pour que l'enfant puisse la déclencher.
                            deleteSelectedAdministrators={handleDeleteButtonClick} // Passe une référence de la méthode pour que l'enfant puisse la déclencher.
                            isDeleteLoading={isDeleteLoading} // Indique si la suppression est en cours ou non.
                        />
                }}
                onRowSelectionModelChange={(newSelection) => {
                    setSelectedAdministratorsIds(newSelection.map(id => Number(id)))
                }}
            />

            {/* Snackbar caché par défaut qui affiche les messages */}
            <TemporarySnackbar
                parentIsSnackbarOpen={isSnackbarOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si le snackbar doit être affiché.
                parentSetIsSnackbarOpen={setIsSnackbarOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                message={snackbarMessage} // Passer un message à afficher dans le snackbar.
                snackbarMessageType={snackbarMessageType} // Passer le type de message pour changer la couleur du snackbar.
            />

            {/* Fenêtre contextuelle de création d'un nouvel administrateur cachée par défaut */}
            <AdministratorCreationDialog
                parentGetAllAdministrators={getAllAdministrators} // Passer une référence de la méthode pour que l'enfant puisse la déclencher.
                parentIsDialogOpen={isCreationDialogOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si a fenêtre contextuelle de création doit être affichée.
                parentSetIsDialogOpen={setIsCreationDialogOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                parentSetSnackbarMessage={setSnackbarMessage} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                parentSetSnackbarMessageType={setSnackbarMessageType} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                parentSetIsSnackbarOpen={setIsSnackbarOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
            />

            {/* Fenêtre contextuelle de confirmation cachée par défaut */}
            <ConfirmationDialog
                parentIsDialogOpen={isConfirmationDialogOpen} // Partager à l'enfant la valeur de la variable d'état pour qu'il sache si a fenêtre contextuelle de confirmation doit être affichée.
                parentSetIsDialogOpen={setIsConfirmationDialogOpen} // Passer une référence de la méthode de changement de la variable d'état pour que l'enfant puisse la déclencher.
                title={"Confirmation de suppression"} // Passer un titre à afficher dans la fenêtre contextuelle de confirmation.
                content={"Êtes-vous sûr de vouloir supprimer les administrateurs sélectionnés?"} // Passer un contenu à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonText={"Supprimer"} // Passer le texte dans bouton de confirmation à afficher dans la fenêtre contextuelle de confirmation.
                confirmationButtonOnClick={deleteSelectedAdministrators} // Passer une référence de la méthode de suppression pour que l'enfant puisse la déclencher.
            />
        </div>
    )
}