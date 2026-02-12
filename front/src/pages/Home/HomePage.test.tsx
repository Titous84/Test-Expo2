import { render, screen } from '@testing-library/react';
import HomePage from './HomePage';
import { MemoryRouter } from 'react-router-dom';

test('renders HomePage', async () => {
  render(<HomePage />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("HomePage");
  expect(linkElement.length === 1).toBeTruthy()
});