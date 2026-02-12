import { render, screen } from '@testing-library/react';
import AdministrationMainPage from './AdministrationMainPage';
import { MemoryRouter } from 'react-router-dom';

test('renders admin page', async () => {
  render(<AdministrationMainPage/>, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("AdministrationMainPage");
  expect(linkElement.length === 1).toBeTruthy()
});