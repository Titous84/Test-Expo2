import { render, screen } from '@testing-library/react';
import LoginPage from './LoginPage';
import { MemoryRouter } from 'react-router-dom';

test('renders connexion', async () => {
  render(<LoginPage />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("pageConnexion");
  expect(linkElement.length === 1).toBeTruthy()
});
