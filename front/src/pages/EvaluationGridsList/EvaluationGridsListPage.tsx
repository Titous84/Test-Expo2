import { Link } from "react-router-dom";
import { Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow } from '@mui/material';
import ButtonExposat from '../../components/button/button-exposat';
import Layout from '../../components/layout/layout';
import { IEvaluationGrid } from "../../types/evaluationGrid/IEvaluationGrid";
import IPage from "../../types/IPage";
import EvaluationGridService from "../../api/evaluationGrid/evaluationGridService";
import { ShowToast } from "../../utils/utils";
import { TEXTS } from '../../lang/fr';
import styles from './EvaluationGridsListPage.module.css';

/**
 * Variables d'état du composant React: EvaluationGridsListPage.
 * @property {IEvaluationGrid[]} evaluationGrids - La liste des modèles de questionnaire.
 */
interface EvaluationGridsListPageState {
    evaluationGrids: IEvaluationGrid[];
}

/**
 * Page de la liste des modèles de questionnaire.
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 */
export default class EvaluationGridsListPage extends IPage<{}, EvaluationGridsListPageState> {
    constructor(props: {}) {
        super(props)

        // Variables d'état
        this.state = {
            evaluationGrids: [],
        }

        // Sert à lier le contexte de la classe aux méthodes.
        this.getEvaluationGrid = this.getEvaluationGrid.bind(this),
        this.deleteEvaluationGrid = this.deleteEvaluationGrid.bind(this)
    }

    componentDidMount() {
        this.getEvaluationGrid();
    }

    /**
     * Récupère les modèles de questionnaire
     */
    async getEvaluationGrid() {
        const response = await EvaluationGridService.getEvaluationGrid();
        if (response && response.data) {
            this.setState({
                evaluationGrids: response.data,
            });
        }
    }

    /**
     * Supprime un modèle de questionnaire
     * @param id L'id du modèle de questionnaire
     */
    deleteEvaluationGrid(id: number) {
        EvaluationGridService.deleteEvaluationGrid(id).then(() => {
            this.getEvaluationGrid();
            ShowToast("Formulaire supprimé avec succès", 5000, "success", "top-center", false);
        }).catch(() => {
            ShowToast("Erreur lors de la suppression du formulaire", 5000, "error", "top-center", false);
        });
    }

    render() {
        return (
            <Layout name={TEXTS.evaluationGrid.title}>
                <Link to={`/gestion-grille-evaluation/formulaire/`}>
                    <ButtonExposat className={styles.buttonCreate} children={TEXTS.evaluationGrid.buttonCreate} />
                </Link>

                <TableContainer component={Paper}>
                    <Table sx={{ minWidth: 650 }} aria-label="simple table">
                        <TableHead>
                            <TableRow>
                                <TableCell>Nom du modèle</TableCell>
                                <TableCell align="right"></TableCell>
                            </TableRow>
                        </TableHead>
                        
                        <TableBody>
                            {this.state.evaluationGrids.map((evaluationGrid) => {
                                return (
                                    <TableRow key={evaluationGrid.id}>
                                        <TableCell>{evaluationGrid.name}</TableCell>
                                        <TableCell align="right">
                                            <Link to={`/gestion-grille-evaluation/formulaire/${evaluationGrid.id}`}>
                                                <ButtonExposat className={styles.buttonEdit} children={TEXTS.evaluationGrid.buttonEdit} />
                                            </Link>
                                            <ButtonExposat className={styles.buttonDelete} onClick={() => this.deleteEvaluationGrid(evaluationGrid.id)} children={TEXTS.evaluationGrid.buttonDelete} />
                                        </TableCell>
                                    </TableRow>
                                )
                            })}
                        </TableBody>
                    </Table>
                </TableContainer>
            </Layout>
        )
    }
}