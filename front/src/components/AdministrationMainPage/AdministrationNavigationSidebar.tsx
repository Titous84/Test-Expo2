import React from "react";
import { List, ListItem, ListItemButton, ListItemIcon, ListItemText, Typography } from "@mui/material";
import { grey } from "@mui/material/colors";
import { ADMINISTRATION_MAIN_PAGE_TABS } from "../../types/AdministrationMainPage/AdministrationMainPageTabs";

/**
 * Props reçues par le composant React: AdministrationNavigationSidebar.
 * @property {function} onTabSelected - Méthode passée par le parent. Appelée quand un onglet est sélectionné. Change l'onglet sélectionné dans le parent.
 */
interface AdministrationNavigationSidebarProps {
    onAdministrationSidebarTabSelected: (tabId: string) => void; // Méthode passée par le parent.
}

/**
 * Barre de navigation affichée à la gauche de la page d'administration seulement.
 * Affiche une liste d'onglets de toutes les contenus à afficher dans la page d'administration.
 * Le contenu dans la zône adjacente à la barre de navigation dans la page d'administration changera selon l'onglet sélectionné.
 */
export default class AdministrationNavigationSidebar extends React.Component<AdministrationNavigationSidebarProps> {
    constructor(props: AdministrationNavigationSidebarProps) {
        super(props)
    }

    render() {
        return (
            <div data-testid="AdministrationNavigationSidebar">
                <Typography variant="h5" sx={{ padding: 2, bgcolor: grey[300] }}>Administration</Typography>
                
                <List component="nav" disablePadding sx={{ width: 300, maxWidth: 300, minHeight: "80vh" }}> {/* `80vh` fait en sorte que la page n'est pas trop petite en hauteur. */}

                    {/* Génère une liste d'onglets à partir de la liste constante ADMINISTRATION_MAIN_PAGE_TABS.
                        Crée un composant React <ListItem> pour chacun des objets de la liste. */}
                    {ADMINISTRATION_MAIN_PAGE_TABS.map((tab => { // `tab` est l'onglet actuel dans la boucle (un objet AdministrationMainPageTab).
                        return (
                            <ListItem key={tab.id} disablePadding> {/* Chaque <ListItem> a besoin d'un id unique. On prend celui dans le tab actuel. */}
                                <ListItemButton
                                    component="a" // `component="a"` : le <ListItemButton> sera considéré comme un bouton. Donc, le curseur de la souris changera pour «pointer» quand on le hover.
                                    onClick={() => this.props.onAdministrationSidebarTabSelected(tab.id)} // Quand cet onglet est cliqué, ça appelle la méthode `onTabSelected()` située dans le composant parent en lui passant l'id de cet onglet.
                                >
                                    <ListItemIcon><tab.icon fontSize="large" sx={{ color: tab.iconColor }} /></ListItemIcon> {/* `<tab.icon>` veut dire affiche le composant React qui se trouve dans la propriété `icon` de l'objet `tab`. */}
                                    <ListItemText primary={tab.primaryText} secondary={tab.secondaryText} />
                                </ListItemButton>
                            </ListItem>
                        )
                    }))}

                </List>
            </div>
        )
    }
}