import { render, screen } from '@testing-library/react';
import LogOutPage from './LogOutPage';

test('renders logout', async () => {
  render(<LogOutPage />);
  const linkElement = await screen.findAllByTestId("logout");
  expect(linkElement.length === 1).toBeTruthy()
});
