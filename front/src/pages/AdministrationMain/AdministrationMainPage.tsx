import { Container, Divider, Stack } from "@mui/material";
import AdministrationNavigationSidebar from "../../components/AdministrationMainPage/AdministrationNavigationSidebar";
import { ADMINISTRATION_MAIN_PAGE_TABS } from "../../types/AdministrationMainPage/AdministrationMainPageTabs";
import IPage from "../../types/IPage";
import React from "react";

/**
 * Variables d'états du composant React: AdministrationMainPage.
 * @property {IPage} componentToDisplayInContentZone - Composant React à afficher dans la zône de contenu de l'onglet sélectionné.
 */
interface AdministrationMainPageState {
    componentToDisplayInContentZone: React.ComponentType<any>;
}

/**
 * Page d'administration
 */
export default class AdministrationMainPage extends IPage<{}, AdministrationMainPageState> {
    constructor(props: {}) {
        super(props)

        // Initialisation des variables d'états.
        this.state = {
            // L'onglet sélectionné par défaut est le premier de la liste.
            componentToDisplayInContentZone: ADMINISTRATION_MAIN_PAGE_TABS[0].componentToDisplayInContentZone
        }
    }

    render() {
        return (
            <div data-testid="AdministrationMainPage">
                <Stack direction="row">
                    {/* Barre latérale de navigation */}
                    {/* Passe une référence de cette méthode à l'enfant. Quand l'enfant appelle cette méthode, elle change la valeur de l'onglet sélectionné. */}
                    <AdministrationNavigationSidebar
                        onAdministrationSidebarTabSelected={this.onSidebarTabSelected}
                    />

                    <Divider orientation="vertical" flexItem />

                    {/* Zône du contenu de l'onglet sélectionné */}
                    <Container>
                        {/* Rendu dynamique du composant React */}
                        {React.createElement(this.state.componentToDisplayInContentZone)}
                    </Container>
                </Stack>
            </div>
        )
    }

    /**
     * Quand un onglet est sélectionné, on change le contenu selon l'onglet sélectionné.
     * @param {string} newTabId - Identifiant de l'onglet qui vient d'être sélectionné.
     */
    onSidebarTabSelected = (newTabId: string) => {
        // Change la variable d'état du composant React affiché dans la zône de contenu.
        // On recherche l'onglet sélectionné dans la liste des onglets et dans cet onglet, il y a le composant React à afficher dans la zône de contenu.
        this.setState({
            componentToDisplayInContentZone: ADMINISTRATION_MAIN_PAGE_TABS.find(tab => tab.id === newTabId)!.componentToDisplayInContentZone
        });
    };
}