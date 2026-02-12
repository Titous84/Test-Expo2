import { Box, Typography } from "@mui/material";
import { TEXTS } from '../../lang/fr';
import AdministratorsTable from "../../components/AdministratorsListPage/AdministrationTable/AdministratorsTable";

/**
 * Page de gestion des administrateurs
 * 
 * Affiche la liste des administrateurs et permet l'ajout, la modification
 * et la suppression d'administrateurs.
 * 
 * @author Antoine Ouellette
 */
export default function AdministratorsListPage() {
    return (
        <div data-testid="administratorsListPage">
            <Box sx={{ mb: 4 }}>
                {/* Titre du contenu */}
                <Typography variant="h4" sx={{ mt:4, mb:2 }}>{TEXTS.administratorsListPage.title}</Typography>

                {/* Tableau de la liste des administrateurs */}
                <AdministratorsTable />
            </Box>
        </div>
    )
}