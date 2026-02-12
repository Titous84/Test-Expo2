import React from 'react';
import { Navigate } from "react-router-dom";
import Actions from "../../api/actions/actions"

/**
 * Page d'envoi par courriel des grilles d'évaluation aux juges.
 * @author Charles Lavoie
 */
export default class JudgesEmailsSendingPage extends React.Component {
    /**
     * @author Charles Lavoie
     * @returns Retourne à la page d'administration
     */
    changePage() {
        return <Navigate to="/administration" />;
    }

    routeEnvoiCourriel() {
        Actions.trySendEvaluation();
        return this.changePage();
    }

    public render(){
        return (
            <div data-testid="emailEvaluationJudge">
                {this.routeEnvoiCourriel()}
            </div>
        );
    }
}