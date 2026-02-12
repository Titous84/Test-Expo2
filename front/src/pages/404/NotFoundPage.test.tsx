import { render, screen } from '@testing-library/react';
import { MemoryRouter } from 'react-router-dom';
import NotFoundPage from './NotFoundPage';

test('renders 404', async () => {
    render(<NotFoundPage />, {wrapper: MemoryRouter});
    const linkElement = await screen.findAllByTestId("404");
    expect(linkElement.length === 1).toBeTruthy()
});