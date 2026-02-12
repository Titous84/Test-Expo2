import { Link } from 'react-router-dom'
import IPage from '../../types/IPage';
import { TEXTS } from "../../lang/fr"
import styles from "./ForbiddenPage.module.css";

/**
 * La page 403
 * Page affichée lorsque l'utilisateur n'a pas la permission d'accéder à l'URL entré.
 */
export default class ForbiddenPage extends IPage {
    public render(){
        return (
            <div className={styles.center} data-testid="403">
                <img src={'./CEGEPV_QUADRICHROMIE.png'} alt="Logo du Cégep de Victoriaville" 
                            className={styles.logoCegep}></img>
                <h1 className={styles.forbidden}> - {TEXTS.forbidden.message403} - </h1>
                <div className={styles.placeLink}>
                    <Link to="/" className={styles.homeLink}>{TEXTS.forbidden.home}</Link>
                </div>
            </div>
        );
    }
}