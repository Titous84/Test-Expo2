import { render, screen } from '@testing-library/react';
import TeamCreationSuccessPage from './TeamCreationSuccessPage';
import { MemoryRouter } from 'react-router-dom';

test('renders create team successful', async () => {
  render(<TeamCreationSuccessPage />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("teamCreation");
  expect(linkElement.length === 1).toBeTruthy()
});
