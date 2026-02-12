import { render, screen } from '@testing-library/react';
import Judge from './JudgesSchedulesPage';

test('renders Information', async () => {
  render(<Judge />)
  const linkElement = await screen.findAllByTestId("Judge-stand");
  expect(linkElement.length === 1).toBeTruthy()
});
