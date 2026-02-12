import React from 'react';
import { render, screen } from '@testing-library/react';
import Footer from './footer';
import { MemoryRouter } from 'react-router-dom';
/**
 * Charles Lavoie
 */
test('renders footer', async () => {
  render(<Footer />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("footer");
  expect(linkElement.length === 1).toBeTruthy()
});
