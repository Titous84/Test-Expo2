import { render, screen } from '@testing-library/react';
import NotFound from './FeatureUnavailablePage';
import { MemoryRouter } from 'react-router-dom';

test('renders not available page', async () => {
  render(<NotFound />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("notAvailable");
  expect(linkElement.length === 1).toBeTruthy()
});
