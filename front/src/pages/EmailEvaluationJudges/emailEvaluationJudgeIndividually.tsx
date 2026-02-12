import React, { useEffect } from 'react';
import { Navigate, useLocation } from "react-router-dom";
import Actions from "../../api/actions/actions";

/**
 * Page d'envoi des courriels d'évaluations vers les juges
 * Code partiellement généré par ChatGPT.
 * @author Tommy Garneau
 * @author OpenAI. (2025). ChatGPT (version 27 février 2025) [Grand modèle de langage].
https://chat.openai.com/chat
 */
const EmailEvaluationJudgeIndividually: React.FC = () => {
    const location = useLocation();
    const judges = location.state?.selectedJudges ?? [];

    // Effectue l'envoi des courriels d'évaluation aux juges sélectionnés
    useEffect(() => {
        const routeEnvoiCourriel = async () => {
            // Vérifie si des juges ont été sélectionnés
            if (judges.length > 0) {
                await Actions.trySendEvaluationIndividually(judges);
            }
        };

        routeEnvoiCourriel();
    }, [judges, location.state]);

    return <Navigate to="/administration" />;
};

export default EmailEvaluationJudgeIndividually;