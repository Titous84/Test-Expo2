import { render, screen } from '@testing-library/react';
import ChangePWForgotten from './ForgottenPasswordModificationPage';

test('renders la page ChangerPWForgotten', async () => {
  render(<ChangePWForgotten />);
  const linkElement = await screen.findAllByTestId("ChangePWF");
  expect(linkElement.length === 1).toBeTruthy()
});
