import { render, screen } from '@testing-library/react';
import CreateJudgeSuccessful from './createJudgeSuccessful';
import { MemoryRouter } from 'react-router-dom';

test('renders create team successful', async () => {
  render(<CreateJudgeSuccessful />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("teamCreation");
  expect(linkElement.length === 1).toBeTruthy()
});
