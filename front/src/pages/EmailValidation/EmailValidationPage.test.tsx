import { render, screen } from '@testing-library/react';
import {EmailValidationPageContent} from './EmailValidationPage';
import { MemoryRouter } from 'react-router-dom';

test('renders emailValidation', async () => {
  render(<EmailValidationPageContent token='Test'/>, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("emailValidation");
  expect(linkElement.length === 1).toBeTruthy()
});
