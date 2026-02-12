import { render, screen } from '@testing-library/react';
import Information from './InformationsPage';

test('renders Information', async () => {
  render(<Information />)
  const linkElement = await screen.findAllByTestId("Information");
  expect(linkElement.length === 1).toBeTruthy()
});
