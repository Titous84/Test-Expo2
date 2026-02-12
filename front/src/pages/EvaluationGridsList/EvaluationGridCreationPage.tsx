import React from 'react';
import { Navigate, useParams } from "react-router";
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator'
import { CircularProgress, Grid } from "@mui/material";
import AlertComposant from "../../components/alert/alert";
import ButtonExposat from '../../components/button/button-exposat';
import Category from "../../types/sign-up/category";
import EvaluationGridFormSection from "../../components/evaluationGrid/evaluationGridFormSection";
import Layout from '../../components/layout/layout';
import { IEvaluationGrid } from "../../types/evaluationGrid/IEvaluationGrid";
import EvaluationGridService from "../../api/evaluationGrid/evaluationGridService";
import { INPUT_VARIANT } from "../../utils/muiConstants";
import { TEXTS } from '../../lang/fr';

/**
 * Page de création d'un modèle de grille d'évaluation.
 * Si le paramètre id est présent, le modèle est modifié, sinon il est créé.
 * @returns La page de création d'un modèle de grille d'évaluation.
 */
export default function EvaluationGridCreationPage(): JSX.Element {
    // Récupère l'id du modèle de grille d'évaluation dans l'URL.
    let { id } = useParams();

    return (
        <EvaluationGridCreationPageContent id={id === undefined ? undefined : Number(id)}></EvaluationGridCreationPageContent>
    );
}

/**
 * Variables d'état du composant React: EvaluationGridCreationPageContent.
 * @property {IEvaluationGrid} evaluationGrid - Le modèle de grille d'évaluation.
 * @property {Category[]} categories - Les catégories de la grille d'évaluation.
 * @property {string[]} error - Les erreurs de l'API.
 * @property {boolean} loading - Si la page est en train de charger.
 * @property {boolean} sendSuccess - Si l'envoie a réussi.
 * @property {boolean} updateMode - Si le modèle est en mode modification.
 */
interface EvaluationGridCreationPageContentState {
    evaluationGrid: IEvaluationGrid;
    categories: Category[];
    error: string[];
    loading: boolean;
    sendSuccess: boolean;
    updateMode: boolean;
}

/**
 * Props pour le composant React: EvaluationGridCreationPageContent.
 * @property {number} id - L'id du modèle de grille d'évaluation.
 */
interface EvaluationGridCreationPageContentProps {
    id?: number;
}

/**
 * Méthode qui vérifie si le champ texte est valide (entre 1 et 255 charactères).
 * @author Thomas-Gabriel Paquin
 * 
 * @param value Valeur à vérifier
 * @returns true si la valeur est un nombre valide, false sinon.
 */
function isValidName(value: string) {
    return !isNaN(value.length) && value.length >= 1 && value.length <= 255;
}

/**
 * Contenu de la page pour créer ou modifier les modèles de questionnaire.
 * Si le paramètre id est présent, le modèle est modifié, sinon il est créé.
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 */
export class EvaluationGridCreationPageContent extends React.Component<EvaluationGridCreationPageContentProps, EvaluationGridCreationPageContentState> {
    constructor(props: EvaluationGridCreationPageContentProps) {
        super(props)

        // Variables d'état.
        this.state = {
            evaluationGrid: {
                id: 0,
                name: "",
                sections: [
                    {
                        id: 0,
                        name: "",
                        position: 1,
                        criterias: [
                            {
                                id: 0,
                                name: "",
                                position: 1,
                                max_value: 1,
                                incremental_value: 1,
                            }
                        ]
                    }
                ]
            },
            categories: [],
            error: [],
            loading: false,
            sendSuccess: false,
            updateMode: false,
        }

        this.handleChangeForm = this.handleChangeForm.bind(this);
    }

    /**
     * Ajoute une section au modèle de grille d'évaluation
     */
    addSection() {
        let oldState : any = {...this.state.evaluationGrid};
        let array : any[] = Array.from(oldState.sections);

        array.push({
            id: 0,
            name: "",
            position: this.state.evaluationGrid.sections.length + 1,
            criterias: [
                {
                    id: 0,
                    name: "",
                    position: 1,
                    max_value: 1,
                    incremental_value: 1,
                }
            ]
        });

        oldState.sections = array;

        this.setState(prevState =>{
            let evaluationGrid = Object.assign({}, prevState.evaluationGrid);
            evaluationGrid = oldState;
            return {evaluationGrid};
        });
    }

    /**
     * Enlève une section du modèle de grille d'évaluation
     * @param position La position de la section à enlever
     */
    removeSection(position: number) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.sections.splice(position, 1);
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Ajoute un critère à une section du modèle de grille d'évaluation
     * @param sectionPosition La position de la section dans le modèle
     */
    addCriteria(sectionPosition: number) {
        let oldState : any = {...this.state.evaluationGrid};

        let object = {
            id: 0,
            name: "",
            position: 0,
            max_value: 1,
            incremental_value: 1,
        };

        object.position = oldState.sections[sectionPosition].criterias.push(object);
        this.setState({evaluationGrid: oldState});
    }

    /**
     * Enlève un critère d'une section du modèle de grille d'évaluation
     * @param sectionPosition La position de la section dans le modèle
     */
    removeCriteria(sectionPosition: number, criteriaPosition: number) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.sections[sectionPosition].criterias.splice(criteriaPosition-1, 1);
        for (let i = criteriaPosition-1; i < evaluationGrid.sections[sectionPosition].criterias.length; i++) {
            evaluationGrid.sections[sectionPosition].criterias[i].position --;
        }
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Vérifie si le composant est en mode modification ou création
     * et valide les champs du formulaire.
     */
    componentDidMount() {
        if (this.props.id !== undefined) {
            this.getEvaluationGridById(Number(this.props.id));
            this.setState({updateMode: true});
        }

        ValidatorForm.addValidationRule('maxLengthName', (value: string) => {
            if (value.length > 50) {
                return false;
            }
            return true;
        });

        ValidatorForm.addValidationRule('SurveyId', (value: number) => {
            if (value === 0) {
                return false;
            }
            return true;
        });
    }

    /**
     * Récupère les modèles de questionnaire
     */
    async getEvaluationGridById(id: number) {
        const response = await EvaluationGridService.getEvaluationGridById(id);
        if (response && response.data) {
            this.setState({ evaluationGrid: response.data });
        }
    }

    /**
     * Modifie le state de evaluationGrid
     * @param event L'événement
     * @param key La clé du state à modifier
     */
    handleChangeForm(event: any, key: string) {
        let evaluationGrid : any = {...this.state};
        evaluationGrid.evaluationGrid[key] = event;
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Modifie le state des critères de evaluationGrid
     * @param event L'événement
     * @param key La clé du state à modifier
     * @param sectionPosition La position de la section dans le state
     * @param criteriaPosition La position du critère dans le state
     */
    handleChangeName(event: any, key: string, name: string) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.name = key;
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Modifie le state des sections de evaluationGrid
     * @param event L'événement
     * @param key La clé du state à modifier
     * @param sectionPosition La position de la section dans le state
     */
    handleChangeSection(event: any, key: string, sectionPosition: number) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.sections[sectionPosition].name = key;
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Modifie le state des critères de evaluationGrid
     * @param event L'événement
     * @param key La clé du state à modifier
     * @param sectionPosition La position de la section dans le state
     * @param criteriaPosition La position du critère dans le state
     */
    handleChangeCriteria(event: any, key: string, sectionPosition: number, criteriaPosition: number) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.sections[sectionPosition].criterias[criteriaPosition].name = key;
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Modifie le state de la pondération de evaluationGrid
     * @param event L'événement
     * @param key La clé du state à modifier
     * @param sectionPosition La position de la section dans le state
     * @param criteriaPosition La position du critère dans le state
     */
    handleChangeValue(event: any, key: number, sectionPosition: number, criteriaPosition: number) {
        let evaluationGrid : any = {...this.state.evaluationGrid};
        evaluationGrid.sections[sectionPosition].criterias[criteriaPosition].max_value = Number(key);
        this.setState({evaluationGrid: evaluationGrid});
    }

    /**
     * Envoie le modèle de grille d'évaluation à l'API
     */
    async onSubmit() {
        this.setState({ loading: true });

        if (this.state.updateMode) {
            const response = await EvaluationGridService.updateEvaluationGrid(this.state.evaluationGrid);
            if (response.error) {
                if (response.error === TEXTS.api.errors.communicationFailed) {
                    this.setState({ error: Array(response.error), loading: false });
                } else {
                    const results: string[] = Object.values(response.error);
                    this.setState({ error: results, loading: false });
                }
                window.scrollTo(0, 0);
            } else if (response.data !== undefined) {
                this.setState({ error: [], sendSuccess: true, loading: false });
            }
        } else {
            const response = await EvaluationGridService.insertEvaluationGrid(this.state.evaluationGrid);
            if (response.error) {
                if (response.error === TEXTS.api.errors.communicationFailed) {
                    this.setState({ error: Array(response.error), loading: false });
                } else {
                    const results: string[] = Object.values(response.error);
                    this.setState({ error: results, loading: false });
                }
                window.scrollTo(0, 0);
            } else if (response.data !== undefined) {
                this.setState({ error: [], sendSuccess: true, loading: false });
            }
        }
    }

    /**
     * Enlève les vérifications de formulaire
     */
    componentWillUnmount() {
        ValidatorForm.removeValidationRule('maxLengthName');
    }

    /**
     * Génère les formulaires de sections
     * @returns Un formulaire de section
     */
    generateSectionsForm() {
        return this.state.evaluationGrid.sections.map((section: any, sectionPosition: number) => {
            return <EvaluationGridFormSection 
                section={section} 
                sectionPosition={sectionPosition} 
                removeSection = {(sectionPosition) => this.removeSection(sectionPosition)} 
                addCriteria={(sectionPosition) => this.addCriteria(sectionPosition)} 
                removeCriteria={(sectionPosition, criteriaPosition) => this.removeCriteria(sectionPosition, criteriaPosition)} 
                handleChangeSection={(key, value, sectionPosition) => this.handleChangeSection(key, value, sectionPosition)} 
                handleChangeCriteria={(key, value, sectionPosition, criteriaPosition) => this.handleChangeCriteria(key, value, sectionPosition, criteriaPosition)} 
                handleChangeValue={(key, value, sectionPosition, criteriaPosition) => this.handleChangeValue(key, value, sectionPosition, criteriaPosition)} />
        })
    }

    /**
     * Génère les alerts lors d'erreur avec l'API.
     * @returns une alert pour chaque erreur
     */
    generateAlert(){
        let counter = 0;
        if(this.state.error.length > 0){
            return this.state.error.map(error => {
                counter++;
                return <AlertComposant key={"alert"+String(counter)} typeAlert="error" errorMessage={error} titleAlert="Erreur" />
            })
        }
        else if(this.state.sendSuccess === true) {
            return <Navigate replace to="/gestion-grille-evaluation" />
        } 
    }

    /**
     * Fait un rendu de la progression de l'envoie du formulaire
     * @returns Un composant de progression
     */
    renderLoading() {
        return (
            <div className="loading">
                <CircularProgress size={50} color="inherit"/>
            </div>
        )
    }

    render() {
        return (
            <>
                {!this.state.loading ? (
                    <Layout isNotContainer name={TEXTS.evaluationGridForm.title}>
                        <ValidatorForm noValidate onSubmit={()=>this.onSubmit()}>
                            {this.generateAlert()}

                            <Grid item xs={9} md={9}>
                                <TextValidator
                                    required
                                    variant={INPUT_VARIANT}
                                    label="Nom du formulaire"
                                    name="name"
                                    fullWidth
                                    onChange={(event:any) => this.handleChangeName('name', event.target.value, this.state.evaluationGrid.name)}
                                    value={this.state.evaluationGrid.name}
                                    validators={['required', 'maxLengthSectionName']}
                                    error={!isValidName(this.state.evaluationGrid.name)}
                                    helperText={!isValidName(this.state.evaluationGrid.name) && TEXTS.evaluationGridForm.name.error.required}
                                    inputProps={{ maxLength: 255 }}
                                />
                                <p>{this.state.evaluationGrid.name.length} / 255</p>
                            </Grid>

                            {this.generateSectionsForm()}

                            <ButtonExposat onClick={()=>this.addSection()} children={"+ ajouter section"}/><br/><br/><br/><br/>
                                
                            <ButtonExposat
                                disabled={
                                    this.state.evaluationGrid.name.length === 0 || 
                                    this.state.evaluationGrid.sections.length === 0 || 
                                    this.state.evaluationGrid.sections.some((section) =>
                                        section.criterias.length === 0 ||
                                        section.criterias.some((criteria) =>
                                            criteria.name.length === 0 ||
                                            criteria.max_value <= 0 ||
                                            criteria.max_value > 100
                                        )
                                    )
                                }
                                onClick={()=>this.onSubmit()} children={this.state.updateMode ? "Modifier" : "Créer"}
                            />
                        </ValidatorForm>
                    </Layout>
                ) : (
                    this.renderLoading()
                )}
            </>
        )
    }
}