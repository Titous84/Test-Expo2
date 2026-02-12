import { Link } from 'react-router-dom'
import IPage from "../../types/IPage";
import { TEXTS } from "../../lang/fr";
import styles from "./HomePage.module.css"

/**
 * Page d'accueil
 */
export default class HomePage extends IPage {
    constructor(props: {}){
        super(props)
        this.state = {}
    }

    public render() {
        return (
            <div data-testid="HomePage">
                <div className={styles.centeredElements}>
                    <div className={styles.homeText}>
                        <h1 className={styles.titleH1}>{TEXTS.homepage.titleH1}</h1>
                        <div>
                            <Link
                                to="/inscription"
                                className={styles.homePageLink + ' ' + styles.btn}
                            >
                                {TEXTS.homepage.signupButton}
                            </Link>
                            <Link
                                to="/informations"
                                className={styles.homePageLink + ' ' + styles.btn}
                            >
                                {TEXTS.homepage.informationsButton}
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}