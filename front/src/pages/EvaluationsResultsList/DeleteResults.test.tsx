import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import EvaluationsResultsListPage from './EvaluationsResultsListPage';
import * as ResultServiceModule from '../../api/result/resultService';
import ResultInfo from '../../types/results/resultInfo';

/**
 * Tests unitaires pour la suppression des résultats dans EvaluationsResultsListPage.
 * 
 * Les tests vérifient que la méthode `deleteJudgeScores` fonctionne correctement
 * en affichant les notifications appropriées et en mettant à jour l'état.
 * 
 * @author Tommy Garneau
 * Code généré par ChatGPT
 * @see https://www.chatgpt.com/
 */

// Simuler le service API
vi.mock('../../api/result/resultService', () => ({
    default: {
        deletesJudgeScore: vi.fn(),
    },
}));

describe('EvaluationsResultsListPage - Suppression des résultats', () => {
    beforeEach(() => {
        // Simuler la méthode `deletesJudgeScore` pour retourner une réponse réussie
        vi.spyOn(ResultServiceModule.default, 'deletesJudgeScore').mockResolvedValue({ data: undefined });
    });

    afterEach(() => {
        // Restaurer les mocks après chaque test
        vi.restoreAllMocks();
    });

    it('1. Affiche une notification de succès lorsqu\'un seul résultat est sélectionné', async () => {
        render(<EvaluationsResultsListPage />);

        // Simuler la sélection d'un seul résultat
        const checkbox = screen.getAllByRole('checkbox')[0];
        fireEvent.click(checkbox);

        // Simuler le clic sur le bouton de suppression
        const deleteButton = screen.getByText('Supprimer les notes sélectionnées');
        fireEvent.click(deleteButton);

        // Vérifier que la méthode API a été appelée
        await waitFor(() => expect(ResultServiceModule.default.deletesJudgeScore).toHaveBeenCalledTimes(1));

        // Vérifier que la notification de succès est affichée
        expect(screen.getByText('Les notes sélectionnées ont été supprimées avec succès.')).toBeInTheDocument();
    });

    it('2. Affiche une notification d\'erreur lorsqu\'aucun résultat n\'est sélectionné', async () => {
        render(<EvaluationsResultsListPage />);

        // Simuler le clic sur le bouton de suppression sans sélectionner de résultat
        const deleteButton = screen.getByText('Supprimer les notes sélectionnées');
        fireEvent.click(deleteButton);

        // Vérifier que la méthode API n'a pas été appelée
        await waitFor(() => expect(ResultServiceModule.default.deletesJudgeScore).not.toHaveBeenCalled());

        // Vérifier que la notification d'erreur est affichée
        expect(screen.getByText('Il n’y a eu aucune modification.')).toBeInTheDocument();
    });

    it('3. Affiche une notification de succès lorsque plusieurs résultats sont sélectionnés', async () => {
        render(<EvaluationsResultsListPage />);

        // Simuler la sélection de plusieurs résultats
        const checkboxes = screen.getAllByRole('checkbox');
        fireEvent.click(checkboxes[0]);
        fireEvent.click(checkboxes[1]);

        // Simuler le clic sur le bouton de suppression
        const deleteButton = screen.getByText('Supprimer les notes sélectionnées');
        fireEvent.click(deleteButton);

        // Vérifier que la méthode API a été appelée pour chaque résultat sélectionné
        await waitFor(() => expect(ResultServiceModule.default.deletesJudgeScore).toHaveBeenCalledTimes(2));

        // Vérifier que la notification de succès est affichée
        expect(screen.getByText('Les notes sélectionnées ont été supprimées avec succès.')).toBeInTheDocument();
    });

    it('4. Affiche une notification de succès lorsque tous les résultats sont sélectionnés', async () => {
        render(<EvaluationsResultsListPage />);

        // Simuler la sélection de tous les résultats
        const selectAllCheckbox = screen.getByRole('checkbox', { name: /select all/i });
        fireEvent.click(selectAllCheckbox);

        // Simuler le clic sur le bouton de suppression
        const deleteButton = screen.getByText('Supprimer les notes sélectionnées');
        fireEvent.click(deleteButton);

        // Vérifier que la méthode API a été appelée pour chaque résultat
        const checkboxes = screen.getAllByRole('checkbox');
        await waitFor(() => expect(ResultServiceModule.default.deletesJudgeScore).toHaveBeenCalledTimes(checkboxes.length - 1)); // -1 pour exclure le "select all"

        // Vérifier que la notification de succès est affichée
        expect(screen.getByText('Les notes sélectionnées ont été supprimées avec succès.')).toBeInTheDocument();
    });
});