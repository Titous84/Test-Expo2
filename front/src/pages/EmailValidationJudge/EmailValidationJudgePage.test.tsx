import { render, screen } from '@testing-library/react';
import EmailValidationJudgePageContent from './EmailValidationJudgePage';
import { MemoryRouter } from 'react-router-dom';

test('renders emailValidationJudge', async () => {
  render(<EmailValidationJudgePageContent token='test'/>, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("emailValidationJudge");
  expect(linkElement.length === 1).toBeTruthy()
});
