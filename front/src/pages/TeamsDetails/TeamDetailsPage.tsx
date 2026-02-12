import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { Box, Button } from '@mui/material';
import { ArrowBack as ArrowBackIcon, Send as SendIcon } from '@mui/icons-material';
import { DataGrid } from '@mui/x-data-grid';
import { EnhancedResultInfo } from '../EvaluationsResultsList/EvaluationsResultsListPage';
import './TeamDetailsPage.css';

/**
 * Composant pour afficher les détails d'une équipe, 
 * les données sont passées via le router en cliquant sur le bouton "détails" de la page d'affichage des résultats
 * pour afficher un tableau détaillé des évaluations par juge.
 * 
 * @returns Le composant de détails d'une équipe 
 * @author Francis Payan
 * Code partiellement généré par ChatGPT et Copilot.
 * @see https://www.chatgpt.com/
 */
const TeamDetailsPage: React.FC = () => {
    const location = useLocation(); // Récupère les données passées via le router (location.state) lors de la navigation entre les pages
    const navigate = useNavigate(); // Hook pour revenir à la page précédente (retour)
    const { teamDetails } = location.state as { teamDetails: EnhancedResultInfo }; // Récupère les détails de l'équipe

    const handleBack = () => {
        navigate(-1); // Navigue à la page précédente
    };

    if (!teamDetails) { // Vérifie si les détails de l'équipe sont disponibles, sinon affiche un message d'erreur 
        return (
            <div className="error-message">
                <p>Aucun détail disponible pour cette équipe. Veuillez retourner à la page précédente et réessayer.</p>
            </div>
        );
    }
    console.log("Détails de l'équipe reçus :", teamDetails); // Affiche les détails de l'équipe dans la console du navigateur pour le débogage

    // Vérifie si tous les juges ont des commentaires pour l'équipe sélectionnée
    if (!teamDetails.judgeScores.every(judge => judge.comments)) {  
        console.error("Erreur: Les commentaires de certains juges ne sont pas disponibles."); 
    }

    /**
     * Crée un tableau de données pour afficher les détails des évaluations par juge.
     * 
     * @returns Un tableau de données pour afficher les détails des évaluations par juge 
     * @author Francis Payan
     * Code partiellement généré par ChatGPT et Copilot.
     * @see https://www.chatgpt.com/
     */
    const data = teamDetails.judgeScores.map((judge, index) => ({
        id: index, // ID nécessaire pour chaque ligne dans DataGrid
        categorie: teamDetails.categorie,
        teamName: teamDetails.teams_name,
        judgeName: judge.judgeName,
        score: judge.score,
        isChecked: judge.isChecked ? "Oui" : "Non",
        comments: judge.comments || "Aucun commentaire",
    }));

    // Colonnes du tableau de données
    const columns = [
        { field: "categorie", headerName: "Catégorie", width: 180 },
        { field: "teamName", headerName: "Nom de l'équipe", width: 200 },
        { field: "judgeName", headerName: "Juge", width: 180 },
        { field: "score", headerName: "Note globale", width: 150 },
        { field: "isChecked", headerName: "Exclu du calcul de la note finale", width: 250 },
        { field: "comments", headerName: "Commentaires", width: 300 },
    ];

    // Fonction pour envoyer les informations par email (à implémenter)
    const handleSendInfo = () => {
        console.log('Envoyer les informations par email');
    };

    // Affiche les détails de l'équipe et le tableau des évaluations par juge
    return (
        <div className="teamDetailsContainer">
            <div className="detailsHeader">
                <Button startIcon={<ArrowBackIcon />} onClick={handleBack} className="backButton">
                    Retour à la page d'affichage des résultats
                </Button>
                <h1>Détails de l'équipe: {teamDetails.teams_name}</h1>
                <div className="finalScore">
                    Note Finale: {teamDetails.finalScore !== null ? `${teamDetails.finalScore} %` : 'Non calculée'}
                </div>
            </div>

            <Box sx={{ height: 400, width: '100%' }}>
                <DataGrid
                    rows={data} // Les données à afficher dans le tableau
                    columns={columns} // Les colonnes définies
                    disableRowSelectionOnClick // Désactive la sélection lors du clic
                    getRowClassName={(params) => params.row.isChecked === "Oui" ? 'excludedRow' : ''} // Applique une classe si exclu du calcul final
                />
            </Box>

            {/* Bouton pour envoyer les informations */}
            <Button
                variant="contained"
                className="sendButton"
                startIcon={<SendIcon />}
                onClick={handleSendInfo}
            >
                Envoyer
            </Button>
        </div>
    );
};

export default TeamDetailsPage;