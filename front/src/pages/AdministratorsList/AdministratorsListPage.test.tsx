import { render, screen } from '@testing-library/react';
import AdministratorsListPage from './AdministratorsListPage';
import { MemoryRouter } from 'react-router-dom';

test('renders AdministratorsListPage', async () => {
  render(<AdministratorsListPage />, {wrapper: MemoryRouter});
  const linkElement = await screen.findAllByTestId("administratorsListPage");
  expect(linkElement.length === 1).toBeTruthy()
});