import { Dialog, DialogTitle, DialogContent, DialogContentText, DialogActions, Button } from '@mui/material';

/**
 * Props du composant React: ConfirmationDialog.
 * @property {boolean} parentIsDialogOpen - Référence à la variable d'état isConfirmationDialogOpen située dans le parent.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsDialogOpen - Référence à la méthode
 * setIsDialogOpen() située dans le parent pour pouvoir changer la valeur de la variable d'état isConfirmationDialogOpen située dans le parent.
 * @property {string} title - Titre à afficher dans la fenêtre contextuelle.
 * @property {string} content - Contenu à afficher dans la fenêtre contextuelle.
 * @property {string} confirmationButtonText - Texte à afficher dans le bouton de confirmation.
 * @property {() => void} confirmationButtonOnClick - Méthode à appeler lorsque le bouton de confirmation est cliqué.
 */
interface ConfirmationDialogProps {
    parentIsDialogOpen: boolean
    parentSetIsDialogOpen: React.Dispatch<React.SetStateAction<boolean>>
    title: string
    content: string
    confirmationButtonText: string
    confirmationButtonOnClick: () => void
}

/**
 * Composant React d'une fenêtre contextuelle de confirmation.
 * De base, la fenêtre n'est pas affichée tant qu'on ne change pas la valeur de isConfirmationDialogOpen à true.
 * 
 * Vous devez déclarer une variable d'état et sa méthode pour la modifier dans votre composant comme ceci:
 * `const [isConfirmationDialogOpen, setIsConfirmationDialogOpen] = React.useState<boolean>(false)`.
 * 
 * @prop {boolean} parentIsSnackbarOpen - Référence à une variable d'état isConfirmationDialogOpen située dans votre composant.
 * @prop {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsSnackbarOpen - Référence vers la méthode
 * setIsConfirmationDialogOpen() qui se trouve dans la déclaration de la variable d'état correspondante de votre composant.
 * @prop {string} title - Titre à afficher dans la fenêtre contextuelle.
 * @prop {string} content - Contenu à afficher dans la fenêtre contextuelle.
 * @prop {string} confirmationButtonText - Texte à afficher dans le bouton de confirmation.
 * @prop {() => void} confirmationButtonOnClick - Méthode à appeler lorsque le bouton de confirmation est cliqué.
 * @returns Un composant React qui affiche une fenêtre contextuelle de confirmation.
 */
export default function ConfirmationDialog(props: ConfirmationDialogProps) {
    return (
        <Dialog
            open={props.parentIsDialogOpen}
            onClose={() => props.parentSetIsDialogOpen(false)}
        >
            <DialogTitle>
                {props.title}
            </DialogTitle>
            <DialogContent>
                <DialogContentText>
                    {props.content}
                </DialogContentText>
            </DialogContent>
            <DialogActions>
                <Button onClick={() => props.parentSetIsDialogOpen(false)}>
                    Annuler
                </Button>
                <Button
                    autoFocus
                    variant="contained"
                    color="error"
                    onClick={() => {
                        // Exécute l'action de confirmation.
                        props.confirmationButtonOnClick()
                        // Ferme la fenêtre contextuelle.
                        props.parentSetIsDialogOpen(false)
                    }}
                >
                    {props.confirmationButtonText}
                </Button>
            </DialogActions>
        </Dialog>
    )
}