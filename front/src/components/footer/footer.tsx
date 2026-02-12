import React from 'react';
import styles from "./footer.module.css"
import { Link } from 'react-router-dom'
import { TEXTS } from "../../lang/fr"
import LogoCegep from './CEGEPV_MONOCHROME_BLANC.png'

interface FooterProps{

}
/**
 * Pied de page de l'application
 */
export default class Footer extends React.Component<FooterProps> {
    constructor(props:FooterProps){
        super(props)
        this.state = {
            
        }
    }
    render(){
        return (
        <div data-testid="footer" className="footer">
            <div className={styles.footer}>
                <div className={styles.centeredBox}>
                    <div className={styles.row}>
                        <div className={styles.column}>
                            <h2>Expo SAT</h2>
                            <hr className={styles.gradientTransparent}></hr>
                            <a href="https://www.cegepvicto.ca/eleves-actuels/exposat/"
                                target="_blank" rel="noreferrer" className={styles.footerLink}>
                                {TEXTS.footer.linkPageOfficiel}</a>
                            <br></br>
                            <a href="https://www.cegepvicto.ca/" target="_blank" rel="noreferrer" className={styles.footerLink}>
                            {TEXTS.footer.linkCegepVicto} {new Date().getFullYear()}</a>
                        </div>
                        <div className={styles.column}>
                            <h2>Équipe de développement</h2>
                            <hr className={styles.gradientTransparent}></hr>
                            <Link to="/liste-developpeurs" className={styles.footerLink}>{TEXTS.footer.listeDev}</Link>
                            <img src={LogoCegep} alt="Logo du Cégep de Victoriaville" 
                            className={styles.logoCegep}>
                            </img>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        );
    }
}