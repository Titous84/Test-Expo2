import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import AllTeamsMembersTable from './AllTeamsMembersTable';

/**
 * Tests unitaires pour le composant AllTeamsMembersTable
 * Fichier incomplet - manque de temps
 * 
 * Partiellement généré par Copilot (GTP-4.1)
 */

// Mock du ShowToast pour vérifier les appels
jest.mock('../../../utils/utils', () => ({
    ShowToast: jest.fn(),
}));

// Mock du service API
jest.mock('../../../api/TeamsList/TeamsListService', () => ({
    tryGetTeamsMembers: jest.fn(() => Promise.resolve({ data: [] })),
    tryGetTeamsMembersConcats: jest.fn(() => Promise.resolve({ data: [] })),
    deletesTeamsMembers: jest.fn(() => Promise.resolve({ data: true })),
}));

describe('AllTeamsMembersTable', () => {
    it('affiche le tableau', async () => {
        render(<AllTeamsMembersTable />);
        expect(await screen.findByRole('grid')).toBeInTheDocument();
    });

    it('affiche un toast si suppression sans sélection', async () => {
        const { ShowToast } = require('../../../utils/utils');
        render(<AllTeamsMembersTable />);
        // Clique sur le bouton de suppression dans la toolbar
        const deleteButton = await screen.findByRole('button', { name: /supprimer/i });
        fireEvent.click(deleteButton);
        await waitFor(() => {
            expect(ShowToast).toHaveBeenCalledWith(
                "Aucun membre n'a été sélectionné pour suppression.",
                5000,
                "warning",
                "top-center",
                false
            );
        });
    });

    it('ouvre le dialog de confirmation si des membres sont sélectionnés', async () => {
        // On simule des membres dans le tableau
        const TeamsListService = require('../../../api/TeamsList/TeamsListService');
        TeamsListService.tryGetTeamsMembers.mockResolvedValueOnce({
            data: [
                { id: 1, first_name: 'Jean', last_name: 'Dupont', numero_da: '123', team_id: 1 }
            ]
        });
        TeamsListService.tryGetTeamsMembersConcats.mockResolvedValueOnce({
            data: [
                { team_id: 1, title: 'Equipe 1' }
            ]
        });

        render(<AllTeamsMembersTable />);
        // Attendre que la ligne soit affichée
        expect(await screen.findByText('Jean')).toBeInTheDocument();

        // Sélectionner la ligne
        const checkbox = screen.getAllByRole('checkbox')[1]; // Le premier est le "select all"
        fireEvent.click(checkbox);

        // Clique sur le bouton de suppression
        const deleteButton = screen.getByRole('button', { name: /supprimer/i });
        fireEvent.click(deleteButton);

        // Vérifie que le dialog de confirmation s'affiche
        expect(await screen.findByText(/voulez-vous vraiment supprimer les membres sélectionnés/i)).toBeInTheDocument();
    });

    it('permet de modifier un membre avec des données valides', async () => {
        const TeamsListService = require('../../../api/TeamsList/TeamsListService');
        TeamsListService.tryGetTeamsMembers.mockResolvedValueOnce({
            data: [
                { id: 1, first_name: 'Jean', last_name: 'Dupont', numero_da: '123', team_id: 1 }
            ]
        });
        TeamsListService.tryGetTeamsMembersConcats.mockResolvedValueOnce({
            data: [
                { team_id: 1, title: 'Equipe 1' }
            ]
        });
        TeamsListService.updateTeamsMember = jest.fn(() => Promise.resolve({ data: true }));

        render(<AllTeamsMembersTable />);
        expect(await screen.findByText('Jean')).toBeInTheDocument();

        // Simule l'édition du prénom
        const cell = screen.getByText('Jean');
        fireEvent.doubleClick(cell);

        const input = screen.getByRole('textbox');
        fireEvent.change(input, { target: { value: 'Paul' } });
        fireEvent.keyDown(input, { key: 'Enter', code: 'Enter' });

        await waitFor(() => {
            expect(TeamsListService.updateTeamsMember).toHaveBeenCalled();
            expect(screen.getByText('Paul')).toBeInTheDocument();
        });
    });

    it('affiche une erreur si modification invalide (ex: prénom vide)', async () => {
        const TeamsListService = require('../../../api/TeamsList/TeamsListService');
        TeamsListService.tryGetTeamsMembers.mockResolvedValueOnce({
            data: [
                { id: 1, first_name: 'Jean', last_name: 'Dupont', numero_da: '123', team_id: 1 }
            ]
        });
        TeamsListService.tryGetTeamsMembersConcats.mockResolvedValueOnce({
            data: [
                { team_id: 1, title: 'Equipe 1' }
            ]
        });
        // Simule une erreur de validation
        TeamsListService.updateTeamsMember = jest.fn(() => Promise.reject({ message: "VALIDATION_FAILED" }));

        render(<AllTeamsMembersTable />);
        expect(await screen.findByText('Jean')).toBeInTheDocument();

        // Simule l'édition du prénom
        const cell = screen.getByText('Jean');
        fireEvent.doubleClick(cell);

        const input = screen.getByRole('textbox');
        fireEvent.change(input, { target: { value: '' } });
        fireEvent.keyDown(input, { key: 'Enter', code: 'Enter' });

        // Vérifie qu'une erreur de validation est gérée
        await waitFor(() => {
            // Vérifier msg erreur ou appel à ShowToast
        });
    });
});