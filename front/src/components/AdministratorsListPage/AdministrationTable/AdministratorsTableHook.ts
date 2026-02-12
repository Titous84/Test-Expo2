import { useState, useEffect } from "react";
import IAdministatorInformation from "../../../types/AdministratorsListPage/AdministratorsTable/IAdministratorInformation";
import { SnackbarMessageType } from "../../TemporarySnackbar/TemporarySnackbar";
import UserService from "../../../api/users/userService";

/**
 * Variables d'état et méthodes pour le composant React: AdministratorsTable.
 * Ceci est un hook React.
 */
export default function useAdministratorsTableHook() {
    // *** Variables d'état ***

    // Liste des administrateurs à afficher dans le tableau.
    const [administratorsList, setAdministratorsList] = useState<IAdministatorInformation[]>([])
    // Liste des administrateurs sélectionnés dans le tableau (liste de leurs ids).
    const [selectedAdministratorsIds, setSelectedAdministratorsIds] = useState<number[]>([])

    // Indique si le tableau est en train de charger ou non (si les données ont été récupérées).
    const [isTableLoading, setIsTableLoading] = useState<boolean>(true)

    // Indique si la suppression est en cours ou non.
    const [isDeleteLoading, setIsDeleteLoading] = useState<boolean>(false)
    
    // Snackbar pour afficher les messages d'erreur ou de succès.
    const [isSnackbarOpen, setIsSnackbarOpen] = useState<boolean>(false) // Pour contrôler si le snackbar est affiché ou non.
    const [snackbarMessage, setSnackbarMessage] = useState<string>("") // Message à afficher dans le snackbar.
    const [snackbarMessageType, setSnackbarMessageType] = useState<SnackbarMessageType>("error") // Type de message à afficher dans le snackbar. Peut être "success", "error", "warning" ou "info".

    // Fenêtre contextuelle de création d'un nouvel administrateur.
    const [isCreationDialogOpen, setIsCreationDialogOpen] = useState<boolean>(false) // Pour contrôler si la fenêtre contextuelle de création d'un nouvel administrateur est affichée ou non.

    // Fenêtre contextuelle de confirmation pour la suppression des administrateurs sélectionnés.
    const [isConfirmationDialogOpen, setIsConfirmationDialogOpen] = useState<boolean>(false) // Pour contrôler si la fenêtre contextuelle de confirmation de la suppression est affichée ou non.

    // *** Méthodes ***
    
    /**
     * Méthode exécutée lorsque le composant est monté.
     */
    useEffect(() => {
        getAllAdministrators() // Récupérer la liste des administrateurs.
    }, []) // Le tableau vide [] signifie que le code doit s'exécuter uniquement au montage.

    /**
     * Récupère la liste des administrateurs et met à jour la variable d'état.
     */
    const getAllAdministrators = () => {
        setIsTableLoading(true) // On affiche la rétroaction de chargement.
        
        UserService.getAllAdministrators()
            .then((administrators) => { // Quand on reçoit une réponse de l'API,
                // On met à jour la liste des administrateurs dans le tableau.
                setAdministratorsList(administrators)
                // On arrête la rétroaction de chargement.
                setIsTableLoading(false)
            })
            .catch((error: Error) => { // En cas d'erreur lors de la récupération des administrateurs
                // Afficher un message d'erreur.
                setSnackbarMessage(error.message) // Le message destiné à l'utilisateur généré par UserService.
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.

                // On arrête la rétroaction de chargement.
                setIsTableLoading(false)
            })
    }

    /**
     * Méthode appelée lorsque le bouton de suppression dans la barre d'outils est cliqué.
     */
    const handleDeleteButtonClick = () => {
        // Si au moins un administrateur est sélectionné, on ouvre la fenêtre contextuelle de confirmation de supression.
        if (selectedAdministratorsIds.length !== 0) {
            // Ouvrir la fenêtre contextuelle de confirmation de supression.
            setIsConfirmationDialogOpen(true)
        } else {
            // Afficher un message d'erreur si aucun administrateur n'est sélectionné.
            setSnackbarMessage("Aucun administrateur sélectionné.")
            setSnackbarMessageType("warning")
            setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
        }
    }

    /**
     * Supprime les administrateurs sélectionnés dans le tableau.
     */
    const deleteSelectedAdministrators = () => {
        setIsDeleteLoading(true) // On affiche la rétroaction de chargement.

        UserService.deleteAdministratorsByIds(selectedAdministratorsIds)
            .then(() => { // Quand l'API a terminé
                // Mettre à jour la liste des administrateurs dans le tableau.
                setAdministratorsList((administratorsList) => {
                    // Si l'administrateur fait partie de la liste selectedAdministratorsIds, on le retire de la liste.
                    return administratorsList.filter((administrator) => {
                        // `!` veut dire de ne pas les garder dans la liste résultante.
                        return !selectedAdministratorsIds.includes(administrator.id)
                    })
                })

                // On arrête la rétroaction de chargement.
                setIsDeleteLoading(false)

                // Afficher un message de succès.
                setSnackbarMessage("Les administrateurs sélectionnés ont été supprimés avec succès.")
                setSnackbarMessageType("success")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            })
            .catch((error: Error) => { // En cas d'erreur lors de la suppression des administrateurs sélectionnés
                // On arrête la rétroaction de chargement.
                setIsDeleteLoading(false)
                
                // Afficher un message d'erreur.
                setSnackbarMessage(error.message) // Le message destiné à l'utilisateur généré par UserService.
                setSnackbarMessageType("error")
                setIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
            })
    }

    // Exposer les variables d'état et les méthodes pour qu'elles soient accessibles dans le composant.
    return {
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
    }
}