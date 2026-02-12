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
    selectedTabId: string;
}

/**
 * Page d'administration
 */
export default class AdministrationMainPage extends IPage<{}, AdministrationMainPageState> {
    constructor(props: {}) {
        super(props)

        // Initialisation des variables d'états.
        const tabFromUrl = this.getTabIdFromUrl();
        const selectedTab = ADMINISTRATION_MAIN_PAGE_TABS.find(tab => tab.id === tabFromUrl) ?? ADMINISTRATION_MAIN_PAGE_TABS[0];

        this.state = {
            // L'onglet sélectionné par défaut est déterminé par l'URL (?onglet=...).
            // @author Nathan Reyes
            componentToDisplayInContentZone: selectedTab.componentToDisplayInContentZone,
            selectedTabId: selectedTab.id
        }
    }



    /**
     * Lie l'onglet actif avec l'URL pour conserver l'état de navigation (bouton retour du navigateur).
     * @author Nathan Reyes
     */
    componentDidMount(): void {
        window.addEventListener("popstate", this.syncTabWithUrl)
        this.syncTabWithUrl()
    }

    componentWillUnmount(): void {
        window.removeEventListener("popstate", this.syncTabWithUrl)
    }

    private getTabIdFromUrl(): string {
        const params = new URLSearchParams(window.location.search)
        return params.get("onglet") ?? ADMINISTRATION_MAIN_PAGE_TABS[0].id
    }

    private updateUrlWithTab(tabId: string): void {
        const url = new URL(window.location.href)
        url.searchParams.set("onglet", tabId)
        window.history.pushState({}, "", url.toString())
    }

    private syncTabWithUrl = () => {
        const tabId = this.getTabIdFromUrl()
        const foundTab = ADMINISTRATION_MAIN_PAGE_TABS.find(tab => tab.id === tabId) ?? ADMINISTRATION_MAIN_PAGE_TABS[0]
        this.setState({
            selectedTabId: foundTab.id,
            componentToDisplayInContentZone: foundTab.componentToDisplayInContentZone
        })
    }

    render() {
        return (
            <div data-testid="AdministrationMainPage">
                <Stack direction="row">
                    {/* Barre latérale de navigation */}
                    {/* Passe une référence de cette méthode à l'enfant. Quand l'enfant appelle cette méthode, elle change la valeur de l'onglet sélectionné. */}
                    <AdministrationNavigationSidebar
                        onAdministrationSidebarTabSelected={this.onSidebarTabSelected}
                        selectedTabId={this.state.selectedTabId}
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
        const foundTab = ADMINISTRATION_MAIN_PAGE_TABS.find(tab => tab.id === newTabId) ?? ADMINISTRATION_MAIN_PAGE_TABS[0];
        this.setState({
            selectedTabId: foundTab.id,
            componentToDisplayInContentZone: foundTab.componentToDisplayInContentZone
        });
        this.updateUrlWithTab(foundTab.id);
    };
}