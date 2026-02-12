import { render, screen } from '@testing-library/react';
import Inscription from './ParticipantRegistrationPage';

test('renders inscription page', async () => {
  render(<Inscription />);
  const linkElement = await screen.findAllByTestId("inscription");
  expect(linkElement.length === 1).toBeTruthy()
});
