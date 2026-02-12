import * as React from 'react';
import { Link } from 'react-router-dom'
import { Box, Button, Drawer, List, ListItem, ListItemText } from '@mui/material';
import MenuIcon from '@mui/icons-material/Menu';
import { grey } from '@mui/material/colors';
import { Path } from '../../router/routes';
import styles from './NavigationBar.module.css'

type Anchor = 'top' | 'left' | 'bottom' | 'right';

/**
 * Props du composant React: MobileNavigationMenuButton.
 * @property {Path[]} links - Liste des liens à afficher dans le menu de navigation.
 */
interface MobileNavigationMenuButtonProps {
    links: Path[]
}

/**
 * Bouton qui ouvre le menu de navigation quand l'écran est petit.
 * Affiche un panneau latéral.
 * @param props 
 * @see https://mui.com/components/menus/
 */
export default function MobileNavigationMenuButton(props:MobileNavigationMenuButtonProps) {
    const [state, setState] = React.useState({
        top: false,
        left: false,
        bottom: false,
        right: false
    });

    const toggleDrawer = (anchor: Anchor, open: boolean) =>
        (event: React.KeyboardEvent | React.MouseEvent) => {
            if (
                event.type === 'keydown' &&
                ((event as React.KeyboardEvent).key === 'Tab' ||
                (event as React.KeyboardEvent).key === 'Shift')
            ) {
                return;
            }
            setState({ ...state, [anchor]: open });
        };

    const list = (anchor: Anchor) => (
        <Box
            sx={{ width: anchor === 'top' || anchor === 'bottom' ? 'auto' : 250 }}
            role="presentation"
            onClick={toggleDrawer(anchor, false)}
            onKeyDown={toggleDrawer(anchor, false)}
        >
            {/* Retrouve les liens en fonction du role sous le menu hamburger */}
            <List>
                {props.links.map(link => (
                    <ListItem key={link.name}>
                        <ListItemText>
                            <Link to={link.path} className={styles.lienHamburger}>{link.name}</Link>
                        </ListItemText>
                    </ListItem>
                ))}
            </List>
        </Box>
    );

    return (
        <div>
            {/* Un menu hamburger affiche */}
            {(['top'] as const).map((anchor) => (
                <React.Fragment key={anchor}>
                    <Button
                        onClick={toggleDrawer(anchor, true)}
                    >
                        <MenuIcon sx={{ color: grey[200] }}/>
                    </Button>

                    <Drawer
                        anchor={anchor}
                        open={state[anchor]}
                        onClose={toggleDrawer(anchor, false)}
                    >
                        {/* Les liens affichent lorsque l'on clique sur l'hamburger */}
                        { list(anchor) }
                    </Drawer>
                </React.Fragment>
            ))}
        </div>
    );
}