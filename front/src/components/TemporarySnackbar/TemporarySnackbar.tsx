import * as React from 'react';
import { Alert, Snackbar, SnackbarCloseReason } from '@mui/material';

/**
 * Valeurs possibles pour le type de message du snackbar.
 */
export type SnackbarMessageType = 'error' | 'warning' | 'info' | 'success';

/**
 * Props du composant React: TemporarySnackbar.
 * @property {boolean} parentIsSnackbarOpen - Référence à la variable d'état isSnackbarOpen située dans le parent.
 * @property {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsSnackbarOpen - Référence à la méthode
 * setIsSnackbarOpen() située dans le parent pour pouvoir changer la valeur de la variable d'état isSnackbarOpen.
 * @property {string} message - Message à afficher dans le snackbar.
 * @property {'error' | 'warning' | 'info' | 'success'} snackbarType - Type de message à afficher dans le snackbar. Influence la couleur du snackbar.
 */
interface TemporarySnackbarProps {
    parentIsSnackbarOpen: boolean
    parentSetIsSnackbarOpen: React.Dispatch<React.SetStateAction<boolean>>
    message: string
    snackbarMessageType: SnackbarMessageType
}

/**
 * Composant React d'un Snackbar qui se ferme automatiquement après 5 secondes.
 * De base, le snackbar n'est pas affiché tant qu'on ne change pas la valeur de isSnackbarOpen à true.
 * 
 * Vous devez déclarer une variable d'état et sa méthode pour la modifier dans votre composant comme ceci:
 * `const [isSnackbarOpen, setIsSnackbarOpen] = React.useState<boolean>(false)`.
 * 
 * @prop {boolean} parentIsSnackbarOpen - Référence à une variable d'état isSnackbarOpen située dans votre composant.
 * @prop {React.Dispatch<React.SetStateAction<boolean>>} parentSetIsSnackbarOpen - Référence vers la méthode
 * setIsSnackbarOpen() qui se trouve dans la déclaration de la variable d'état correspondante de votre composant.
 * @prop {string} message - Message à afficher dans le snackbar.
 * @returns Un composant React qui affiche un snackbar.
 * 
 * Inspiré de https://mui.com/material-ui/react-snackbar/#automatic-dismiss
 * Modifié par Antoine Ouellette
 */
export default function TemporarySnackbar(props: TemporarySnackbarProps) {
    /**
     * Méthode déclenchée à chaque fois qu'un événement essaie de fermer le Snackbar.
     * 
     * On veut intercepter les événements qui fermeraient le snackbar pour les empêcher.
     * Par exemple, on veut prévenir que le Snackbar se ferme si l'utilisateur clique dans l'écran.
     * @param event Événement de fermeture du Snackbar.
     * @param reason Raison de la fermeture du Snackbar (passée par MUI).
     */
    const handleCloseEvents = (
        event: React.SyntheticEvent | Event,
        reason?: SnackbarCloseReason
    ) => {
        // Si l'utilisateur clique dans l'écran (clickaway correspond à un clic en dehors du snackbar).
        if (reason === 'clickaway') {
            return // On ignore l'événement et on ne ferme pas le snackbar.
        }

        // Sinon, si l'utilisateur ferme le snackbar en cliquant sur le X ou si ça fait 5 secondes.
        props.parentSetIsSnackbarOpen(false) // Exécute la méthode dans le parent qui change la valeur de la variable d'état.
    }

    return (
        <Snackbar
            open={props.parentIsSnackbarOpen} // Gère si le snackbar est affiché ou non.
            autoHideDuration={5000} // Délai avant la fermeture automatique (5 secondes).
            onClose={handleCloseEvents} // Déclenchée à chaque fois qu'un événement essaie de fermer le Snackbar.
            anchorOrigin={{ vertical: "top", horizontal: "center" }} // Position du snackbar (en haut au centre de l'écran).
        >
            {/* Composant qui change le style selon le type de message */}
            <Alert
                onClose={handleCloseEvents}
                severity={props.snackbarMessageType} // Change la couleur selon le type de message.
                variant="filled"
                sx={{ width: '100%' }}
            >
                {props.message} {/* Message à afficher dans le snackbar. */}
            </Alert>
        </Snackbar>
    )
}