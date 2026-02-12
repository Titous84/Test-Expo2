import React from 'react';
import { Link } from 'react-router-dom'
import { TEXTS } from "../../lang/fr"
import styles from "./FeatureUnavailablePage.module.css";

interface FeatureUnavailablePageProps{

}
interface FeatureUnavailablePageState{

}
/**
 * Page affichée quand une fonctionnalité n'est pas disponible.
 */
 export default class FeatureUnavailablePage extends React.Component<FeatureUnavailablePageProps, FeatureUnavailablePageState> {
    constructor(props:FeatureUnavailablePageProps){
        super(props)
        this.state = {
            
        }
    }
    public render(){
        /**
         * 
         * Changer les rôles pouvant accéder à cette page pour seulement admin
         * 
         */
        return (
            <div className={styles.center} data-testid="notAvailable">
                <img src={'./CEGEPV_QUADRICHROMIE.png'} alt="Logo du Cégep de Victoriaville" 
                            className={styles.logoCegep}></img>
                <h1 className={styles.notFound}> - {TEXTS.notAvailable.message} - </h1>
                <div className={styles.placeLink}>
                    <Link to="/administration" className={styles.homeLink}>{TEXTS.notAvailable.admin}</Link>
                </div>
            </div>
        );
    }
}