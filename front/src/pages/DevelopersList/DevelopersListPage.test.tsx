import { render, screen } from '@testing-library/react';
import IPage from "../../types/IPage";
import DevelopersListPage from './DevelopersListPage';

/**
 * @author Charles Lavoie
 */
test('renders developers page', async () => {
    render(<DevelopersListPage />);
    const linkElement = await screen.findAllByTestId("listedeveloppeurs");
    expect(linkElement.length === 1).toBeTruthy()
});

test('DevList returns not null array', async () => {
    const devList = new DevelopersListPage({});

    const generate = devList.generateDevelopersList();
    expect(generate.length >= 1).toBeTruthy()
});