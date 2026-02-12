import { render, screen } from '@testing-library/react';
import JudgesEmailsSendingPage from './JudgesEmailsSendingPage';
import { MemoryRouter } from 'react-router-dom';

test('renders emailEvaluationJudge', async () => {
  render(<JudgesEmailsSendingPage />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("emailEvaluationJudge");
  expect(linkElement.length === 1).toBeTruthy()
});
