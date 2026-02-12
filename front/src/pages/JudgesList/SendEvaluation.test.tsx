import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { render, screen, fireEvent } from '@testing-library/react';
import NavigateButton from './JudgesListPage';
import Judge from '../../types/judge';
import { useNavigate } from 'react-router';

/**
 * Tests unitaires pour le bouton d'envoi des évaluations par courriel.
 * 
 * Les tests vérifient que le bouton fonctionne correctement en fonction des juges sélectionnés.
 * 
 * @author Tommy Garneau
 * Code généré par ChatGPT
 * @see https://www.chatgpt.com/
 */

// Mock de `useNavigate` pour vérifier les redirections
vi.mock('react-router', () => ({
    useNavigate: vi.fn(),
}));

const mockNavigate = useNavigate as jest.Mock;

describe('NavigateButton', () => {
    beforeEach(() => {
        mockNavigate.mockClear();
    });

    it('1. Affiche une notification de succès lorsqu\'un seul juge est sélectionné', () => {
        const selectedJudges: Judge[] = [
    {
        id: 1,
        firstName: 'John',
        lastName: 'Doe',
        email: 'john.doe@example.com',
        category: 'Catégorie A',
        activated: true,
        blacklisted: false,
        pictureConsent: true, 
        pwd: '', 
        pwdconfirm: '', 
    },
];
      
        const button = screen.getByRole('button', { name: /envoyer les évaluations/i });
        fireEvent.click(button);

        expect(mockNavigate).toHaveBeenCalledWith('/envoiEvaluationsJugeIndividuelle', {
            state: { selectedJudges },
        });
    });

    it('2. Affiche une notification de succès lorsque plusieurs juges sont sélectionnés', () => {
        const selectedJudges: Judge[] = [
            { id: 1, firstName: 'Juge', lastName: 'Cool', email: 'juge.cool@example.com', category: 'Catégorie A', activated: true, blacklisted: false, pictureConsent: true, pwd: '', pwdconfirm: '' },
            { id: 2, firstName: 'Tommy', lastName: 'Garneau', email: 'tommy.garneau@example.com', category: 'Catégorie B', activated: true, blacklisted: false, pictureConsent: true, pwd: '', pwdconfirm: '' },
        ];

        

        const button = screen.getByRole('button', { name: /envoyer les évaluations/i });
        fireEvent.click(button);

        expect(mockNavigate).toHaveBeenCalledWith('/envoiEvaluationsJugeIndividuelle', {
            state: { selectedJudges },
        });
    });

    it('3. Affiche une notification d\'erreur lorsqu\'aucun juge n\'est sélectionné', () => {
        const selectedJudges: Judge[] = [];

      

        const button = screen.getByRole('button', { name: /envoyer les évaluations/i });
        fireEvent.click(button);

        expect(mockNavigate).not.toHaveBeenCalled();
        expect(console.warn).toHaveBeenCalledWith('Aucun juge sélectionné pour l\'envoi d\'évaluation.');
    });

    it('4. Affiche une notification de succès lorsque tous les juges sont sélectionnés', () => {
        const selectedJudges: Judge[] = [
            { id: 1, firstName: 'John', lastName: 'Doe', email: 'john.doe@example.com', category: 'Catégorie A', activated: true, blacklisted: false, pictureConsent: true, pwd: '', pwdconfirm: '' },
            { id: 2, firstName: 'Jane', lastName: 'Smith', email: 'jane.smith@example.com', category: 'Catégorie B', activated: true, blacklisted: false, pictureConsent: true, pwd: '', pwdconfirm: '' },
            { id: 3, firstName: 'Alice', lastName: 'Johnson', email: 'alice.johnson@example.com', category: 'Catégorie C', activated: true, blacklisted: false, pictureConsent: true, pwd: '', pwdconfirm: '' },
        ];


        const button = screen.getByRole('button', { name: /envoyer les évaluations/i });
        fireEvent.click(button);

        expect(mockNavigate).toHaveBeenCalledWith('/envoiEvaluationsJugeIndividuelle', {
            state: { selectedJudges },
        });
    });
});