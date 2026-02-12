import { render, screen } from '@testing-library/react';
import EvaluationGridsListPage from './EvaluationGridsListPage';

/* Teste de v/rification si la page d'evaluationGrid est bien ouverte et fonctionnelle.
* @author Thomas-Gabriel Paquin
*/
test('renders evaluationGrid page', async () => {
  render(<EvaluationGridsListPage />)
  const linkElement = await screen.findAllByTestId("evaluationGrid");
  expect(linkElement.length === 1).toBeTruthy()
});