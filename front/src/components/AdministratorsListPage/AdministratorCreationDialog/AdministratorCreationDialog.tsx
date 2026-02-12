import { useState } from "react";
import { Button, Dialog, DialogActions, DialogContent, DialogTitle, IconButton, Stack, TextField } from "@mui/material"
import CloseIcon from '@mui/icons-material/Close'
import UserService from "../../../api/users/userService";
import { REGEX } from "../../../utils/constants";
import { SnackbarMessageType } from "../../TemporarySnackbar/TemporarySnackbar";

/**
 * Props du composant React: AdministratorsCreationDialog.
 * @property {boolean} parentIsDialogOpen - Référence à la variable d'état isCreationDialogOpen située dans le parent.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsDialogOpen - Référence à la méthode
 * setIsCreationDialogOpen() située dans le parent pour pouvoir changer la valeur de la variable d'état isCreationDialogOpen.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsSnackbarOpen - Référence à la méthode
 * setIsCreationDialogOpen() située dans le parent pour pouvoir changer la valeur de la variable d'état isCreationDialogOpen.
 * @property {React.Dispatch<React.SetStateAction<string>>} parentSetSnackbarMessage - Référence à la méthode
 * setSnackbarMessage() située dans le parent pour pouvoir changer la valeur de la variable d'état snackbarMessage.
 * @property {React.Dispatch<React.SetStateAction<string>>} SetSnackbarMessageType - Référence à la méthode
 * setSnackbarMessageType() située dans le parent pour pouvoir changer la valeur de la variable d'état snackbarMessageType.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} SetIsSnackbarOpen - Référence à la méthode
 * setIsSnackbarOpen() située dans le parent pour pouvoir changer la valeur de la variable d'état isSnackbarOpen.
 */
interface AdministratorCreationDialogProps {
    parentGetAllAdministrators: () => void
    parentIsDialogOpen: boolean
    parentSetIsDialogOpen: React.Dispatch<React.SetStateAction<boolean>>
    parentSetSnackbarMessage: React.Dispatch<React.SetStateAction<string>>
    parentSetSnackbarMessageType: React.Dispatch<React.SetStateAction<SnackbarMessageType>>
    parentSetIsSnackbarOpen: React.Dispatch<React.SetStateAction<boolean>>
}

/**
 * Fenêtre contextuelle qui apparait au dessus du tableau pour créer un nouvel administrateur.
 * Contient un formulaire pour entrer les informations de l'administrateur à créer.
 * 
 * De base, la fenêtre n'est pas affichée tant qu'on ne change pas la valeur de isCreationDialogOpen à true.
 * 
 * Vous devez déclarer une variable d'état et sa méthode pour la modifier dans votre composant comme ceci:
 * `const [isCreationDialogOpen, setIsCreationDialogOpen] = React.useState<boolean>(false)`.
 * 
 * @prop {boolean} parentIsSnackbarOpen - Référence à une variable d'état isCreationDialogOpen située dans votre composant.
 * @prop {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsDialogOpen - Référence vers la méthode
 * setIsCreationDialogOpen() qui se trouve dans la déclaration de la variable d'état correspondante de votre composant.
 */
export default function AdministratorCreationDialog(props: AdministratorCreationDialogProps) {
    const [email, setEmail] = useState("");
    const [emailErrorMessage, setEmailErrorMessage] = useState("");
    
    const [password, setPassword] = useState("");
    const [passwordErrorMessage, setPasswordErrorMessage] = useState("");

    // Indique si le formulaire de création est en train de créer un nouvel administrateur ou non.
    const [isLoading, setIsLoading] = useState(false);

    /**
     * Validation de l'adresse courriel.
     * Affiche un message d'erreur si l'adresse courriel est vide
     * ou si elle n'est pas au bon format.
     */
    const validateEmail = () => {
        if (!email) {
            setEmailErrorMessage("Le courriel ne peut pas être vide.")
        } else if (!REGEX.EMAIL.test(email)) {
            setEmailErrorMessage("Le format du courriel n’est pas valide.\nIl doit respecter le format \"courriel@domaine.suffixe\".")
        } else {
            setEmailErrorMessage("")
        }
    }

    /**
     * Validation du mot de passe.
     * Affiche un message d'erreur si le mot de passe est vide.
     */
    const validatePassword = () => {
        if (!password) {
            setPasswordErrorMessage("Le mot de passe ne peut pas être vide.");
        } else {
            setPasswordErrorMessage("");
        }
    };

    /**
     * Crée un nouvel administrateur.
     * Utilise les valeurs des champs de texte.
     */
    const createAdministrator = () => {
        validateEmail()
        validatePassword()

        if (!emailErrorMessage && !passwordErrorMessage) {
            setIsLoading(true) // On affiche la rétroaction de chargement.
            
            // Créer le nouvel administrateur.
            UserService.createAdministrator(email, password)
                .then(() => { // Quand l'API a terminé
                    // Vider les champs de texte.
                    setEmail("")
                    setPassword("")

                    // Mettre à jour la liste des administrateurs dans le tableau.
                    props.parentGetAllAdministrators()

                    // Arrêter la rétroaction de chargement.
                    setIsLoading(false)

                    // Fermer la fenêtre contextuelle de création.
                    props.parentSetIsDialogOpen(false)
    
                    // Afficher un message de succès.
                    props.parentSetSnackbarMessage("L'administrateur a été créé avec succès.")
                    props.parentSetSnackbarMessageType("success")
                    props.parentSetIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                })
                .catch((error: Error) => { // En cas d'erreur lors de la suppression des administrateurs sélectionnés
                    // Arrêter la rétroaction de chargement.
                    setIsLoading(false)

                    // Afficher un message d'erreur.
                    props.parentSetSnackbarMessage(error.message) // Le message destiné à l'utilisateur généré par UserService.
                    props.parentSetSnackbarMessageType("error")
                    props.parentSetIsSnackbarOpen(true) // Déclencher l'affichage du snackbar.
                })
        }
    }

    return (
        <div data-testid="administratorsCreationDialog">
            <Dialog
                open={props.parentIsDialogOpen}
                onClose={() => props.parentSetIsDialogOpen(false)}
            >
                <Stack direction="row" justifyContent="space-between" alignItems="center">
                    <DialogTitle sx={{ pb:0 }}>Créer un administrateur</DialogTitle>
                    <div>
                        <IconButton
                            aria-label="close"
                            onClick={() => props.parentSetIsDialogOpen(false)}
                            sx={(theme) => ({
                                color: theme.palette.grey[500],
                                mr: 1
                            })}
                        >
                            <CloseIcon />
                        </IconButton>
                    </div>
                </Stack>
                <DialogContent sx={{ width: 500 }}>
                    <Stack spacing={2} sx={{ width: 450 }}>
                        <TextField
                            autoFocus
                            required
                            id="email"
                            name="email"
                            label="Courriel"
                            type="email"
                            fullWidth
                            variant="standard"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                            onBlur={validateEmail}
                            error={!!emailErrorMessage}
                            helperText={emailErrorMessage}
                        />
                        <TextField
                            required
                            id="password"
                            name="password"
                            label="Mot de passe"
                            type="password"
                            fullWidth
                            variant="standard"
                            value={password}
                            onChange={(event) => setPassword(event.target.value)}
                            onBlur={validatePassword}
                            error={!!passwordErrorMessage}
                            helperText={passwordErrorMessage}
                        />
                    </Stack>
                </DialogContent>
                <DialogActions>
                    <Button
                        variant="contained"
                        loading={isLoading}
                        onClick={createAdministrator} // Créer un nouvel administrateur.
                    >
                        Créer
                    </Button>
                </DialogActions>
            </Dialog>
        </div>
    )
}