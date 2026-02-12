import { render, screen } from '@testing-library/react';
import Inscription from './JudgeCreationPage';

//Test qui vérifie que la page d'inscription du juge s'affiche
test('renders  la page d\'inscription du juge', async () => {
  render(<Inscription />);
  const linkElement = await screen.findAllByTestId("inscriptionJuge");
  expect(linkElement.length === 1).toBeTruthy()
});