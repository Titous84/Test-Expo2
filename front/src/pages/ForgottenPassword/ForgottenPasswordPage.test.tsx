import { render, screen } from '@testing-library/react';
import PageForgotPW from './ForgottenPasswordPage';
import { MemoryRouter } from 'react-router-dom';

test('renders page ForgotPW', async () => {
  render(<PageForgotPW/>, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("motDePasseOublier");
  expect(linkElement.length === 1).toBeTruthy()
});
