import { render, screen } from '@testing-library/react';
import { JudgeEvaluationsListPageContent } from './JudgeEvaluationsListPage';

test('renders page judge-survey', async () => {
  render(<JudgeEvaluationsListPageContent />);
  const linkElement = await screen.findAllByTestId("judge-survey");
  expect(linkElement.length === 1).toBeTruthy()
});
