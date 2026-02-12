import { Link } from 'react-router-dom'
import IPage from '../../types/IPage';
import { TEXTS } from "../../lang/fr"
import styles from "./NotFoundPage.module.css";

/**
 * La page 404
 * Page affichée lorsque l'utilisateur entre une URL qui n'existe pas.
 */
export default class NotFoundPage extends IPage {
    public render(){
        return (
            <div className={styles.center} data-testid="404">
                <img
                    src={'./CEGEPV_QUADRICHROMIE.png'}
                    alt="Logo du Cégep de Victoriaville" 
                    className={styles.logoCegep}
                ></img>
                <h1 className={styles.title}> - 404 - </h1>
                <h1 className={styles.notFound}> - {TEXTS.notFound.message404} - </h1>
                <div className={styles.placeLink}>
                    <Link to="/" className={styles.homeLink}>{TEXTS.notFound.home}</Link>
                </div>
            </div>
        );
    }
}