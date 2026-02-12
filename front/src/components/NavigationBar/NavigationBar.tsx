import React from 'react';
import { Link } from 'react-router-dom'
import MobileNavigationMenuButton from './MobileNavigationDrawer'
import { Path } from '../../router/routes'
import Logo from "./LOGO EXPOSAT_2025.png";
import styles from './NavigationBar.module.css'

/**
 * Props du composant React: NavigationBar.
 * @property {Path[]} links - Liste des liens à afficher dans la barre de navigation.
 */
export interface NavigationBarProps{
    links: Path[]
}

/**
 * Barre de navigation du site web quand l'écran est grand.
 */
export default class NavigationBar extends React.Component<NavigationBarProps> {
    constructor(props: NavigationBarProps){
        super(props)

        // Initialisation des variables d'état.
        this.state = {
            links: []
        }
    }

    /**
     * Méthode pour générer les composants JSX des liens dans la barre de navigation.
     * @returns une liste de composants JSX qui sont les liens à afficher dans la barre de navigation.
     */
    generateLinks() : JSX.Element[] {
        // Retourne tous les liens selon le rôle (les permissions) de l'utilisateur.
        return this.props.links.map(link => {
            //Le nom du lien est celui se retrouvant dans les routes Path = []
            if (link.position === "Left") {
                return (
                    <li key={link.path} className={styles.positionGauche}>
                        <Link to={link.path} className={styles.lienMenu}>
                            {link.name}
                        </Link>
                    </li>
                );
            } else {
                return (
                    <li key={link.path} className={styles.positionDroit}>
                        <Link to={link.path} className={styles.lienMenu}>
                            {link.name}
                        </Link>
                    </li>
                );
            }
        })
    }

    /*
     * Contient une liste des éléments dans la barre menu,
     * Le contenu change en fonction de la résolution.
     */
    render(){
        const liensPossibles = this.props.links
        return (
            <div data-testid="navbar" className={styles.backgroundElements + " " + styles.block}>
                <li className={styles.positionGauche}>
                    <Link to="/">
                        <img
                            src={Logo}
                            alt="Logo du Cégep de Victoriaville"
                            className={styles.logoCegep}
                        />
                    </Link>
                </li>

                {/* Partie a afficher sur un ecran d'ordinateur */}
                <div className={styles.menuGlobal}>
                    <ul className={styles.backgroundElements}>
                        {this.generateLinks()}
                    </ul>
                </div>

                {/* Partie a afficher lorsque l'ecran est plus petit */}
                <div className={styles.hamburger}>
                    <li className={styles.positionDroit}>
                        <MobileNavigationMenuButton links={liensPossibles}/>
                    </li>
                </div>
            </div>
        );
    }
}