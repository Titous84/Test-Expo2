import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import ForbiddenPage from './ForbiddenPage';

test('renders 403', async () => {
    render(<ForbiddenPage />, {wrapper: MemoryRouter});
    const linkElement = await screen.findAllByTestId("403");
    expect(linkElement.length === 1).toBeTruthy()
});