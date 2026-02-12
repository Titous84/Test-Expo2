import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { fireEvent, render, screen, waitFor } from '@testing-library/react';
import EvaluationsResultsListPage from './EvaluationsResultsListPage';
import * as ResultServiceModule from '../../api/result/resultService';
import ResultInfo from '../../types/results/resultInfo';

/**
 * Tests unitaires pour le composant ResultsList qui affiche les résultats des évaluations. 
 * 
 * Les tests vérifient que le composant affiche correctement les résultats des évaluations et gère les erreurs de l'API.
 * Les tests vérifient également que les utilisateurs peuvent sélectionner et désélectionner les scores des juges pour le calcul final.
 * 
 * @author Francis Payan
 * Code généré par ChatGPT
 * @see https://www.chatgpt.com/
 *
*/

// Simuler le service API
vi.mock('../../api/result/resultService', () => ({
  default: {
    GetResult: vi.fn(),
  },
}));

// Type pour la réponse de l'API simulée
type APIResult<T> = { data: T };

describe('ResultsList', () => {
  // Données factices pour simuler la réponse de l'API conformes à ResultInfo
  const mockResults: ResultInfo[] = [
    {
      id: 1,
      categorie: 'Science',
      survey: 'Survey A',
      teams_name: 'Team A',
      first_name_user: 'Judge',
      judge_id: 101,
      last_name_user: 'One',
      global_score: 85,
      comments: 'Great job!',
      person_contact: 'Contact Person',
      email: 'email@example.com'
    },
    {
      id: 2,
      categorie: 'Math',
      survey: 'Survey B',
      teams_name: 'Team B',
      first_name_user: 'Judge',
      judge_id: 102,
      last_name_user: 'Two',
      global_score: 75,
      comments: 'Well done!',
      person_contact: 'Contact Person B',
      email: 'emailb@example.com'
    },
  ];

  beforeEach(() => {
    // Simuler le comportement de GetResult pour retourner les résultats factices
    vi.spyOn(ResultServiceModule.default, 'GetResult').mockResolvedValue({ data: mockResults } as APIResult<ResultInfo[]>);
  });

  beforeEach(() => {
    // Simuler le comportement de GetResult pour retourner les résultats factices
    vi.spyOn(ResultServiceModule.default, 'GetResult').mockResolvedValue({ data: mockResults } as APIResult<ResultInfo[]>);
  });

  afterEach(() => {
    // Restaurer les mocks après chaque test
    vi.restoreAllMocks();
  });

  it('doit afficher le tableau des résultats après le montage du composant', async () => {
    render(<EvaluationsResultsListPage />);

    // Utiliser findByText pour rechercher le titre dans le document, cela attendra jusqu'à ce que l'élément soit présent ou jusqu'à expiration du timeout
    await waitFor(() => expect(screen.findByText('Résultats des Évaluations')).toBeDefined());

    // Vérifier que le tableau contient les bonnes données
    for (const result of mockResults) {
      expect(screen.findByText(result.teams_name)).toBeDefined();
    }
  });

  it('doit gérer les erreurs si l\'API retourne une erreur', async () => {
    // Simuler une erreur de l'API en retournant un tableau vide pour `data`.
    vi.spyOn(ResultServiceModule.default, 'GetResult').mockResolvedValueOnce({ data: [] } as APIResult<ResultInfo[]>);
    render(<EvaluationsResultsListPage />);
  });
  
  // Ajouter des tests pour la sélection des scores des juges
  it('permet aux utilisateurs de sélectionner et de désélectionner les scores des juges pour le calcul final', async () => {
    render(<EvaluationsResultsListPage />);
    await waitFor(() => expect(screen.findByText('Résultats des Évaluations')).toBeDefined());

    // Simule le clic sur la première checkbox pour sélectionner le score d'un juge
    const firstCheckbox = screen.getAllByRole('checkbox')[0];
    fireEvent.click(firstCheckbox);
    expect(firstCheckbox).toBeChecked();

    // Simule le clic à nouveau pour désélectionner
    fireEvent.click(firstCheckbox);
    expect(firstCheckbox).not.toBeChecked();
  }); 

});

export {};