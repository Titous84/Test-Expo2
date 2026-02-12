import { IconButton, Tooltip } from '@mui/material';
import { Add, Edit, Logout } from '@mui/icons-material';
import InformationsEditor from '../../components/informations/informationsEditor/informationsEditor';
import Layout from '../../components/layout/layout';
import MDContentViewer from '../../components/markdown/mdContentViewer';
import InformationBlockInfo from '../../types/informations/informationBlockInfo';
import IPage from '../../types/IPage';
import ConnectionService from '../../api/connection/connectionService';
import InformationService from '../../api/informations/informationService';
import { sortByNumber } from '../../utils/utils';
import { TEXTS } from '../../lang/fr';
import styles from "./InformationsPage.module.css";

/**
 * Variables d'état du composant React: InformationsPage.
 * @property {InformationBlockInfo[]} informations - Liste des blocs d'informations.
 */
interface InformationsPageState{
    informations:InformationBlockInfo[];
}

/**
 * Page qui affiche les informations d'ExpoSAT.
 * @author Mathieu Sévégny
 */
export default class InformationsPage extends IPage<{}, InformationsPageState> {
    constructor(props: {}){
        super(props)

        // Variables d'état
        this.state = {
            informations:[]
        }

        // Sert à lier le contexte de la classe aux méthodes.
        this.onUpdate = this.onUpdate.bind(this)
    }

    /**
     * Variables privées.
     * Ici ce n'est pas un state pour ne pas que l'usager le change facilement.
     */
    modifying: boolean = false;
    isAdmin: boolean = false;

    /**
     * Récupére les informations lors de l'ouverture de la page.
     */
    componentDidMount(){
        this.getInfos();
    }

    /**
     * Récupère les informations nécessaires.
     */
    async getInfos(){
        const responseInfos = await InformationService.getInformations();
        if (responseInfos && responseInfos.data){
            this.setState({
                informations:responseInfos.data.sort((a,b)=>sortByNumber(a.order,b.order))
            })
        }
        const role = await ConnectionService.tryGetUserRole();
        if (role && role.data){
            this.isAdmin = role.data.name === "Admin";
        }
        
        this.forceUpdate();
    }

    /**
     * Génère les boutons appropriés à l'état actuel.
     */
    generateButtons(){
        //Si l'usager n'est pas admin, il ne doit pas afficher de boutons.
        if (!this.isAdmin) return <></>
        
        if (!this.modifying){
            //Affichage mode lecture
            return <div className="rightDiv">
                <Tooltip id="button-report" title={TEXTS.informations.buttons.modifyGeneral}>
                    <IconButton id="openEditor" aria-label="upload picture" component="span" onClick={() => this.toggleModification()}>
                        <Edit />
                    </IconButton>
                </Tooltip>
            </div>
        }
        else{
            //Affichage mode édition
            return <div className={`rightDiv ${styles.modifying}`}>
                <Tooltip id="button-report" title={TEXTS.informations.buttons.add}>
                    <IconButton id="addButton" aria-label="upload picture" component="span" onClick={() => this.createInformationBlock()}>
                        <Add />
                    </IconButton>
                </Tooltip>
                <Tooltip id="button-report" title={TEXTS.informations.buttons.quitEdition}>
                    <IconButton id="closeEditor" aria-label="upload picture" component="span" onClick={() => this.toggleModification()}>
                        <Logout />
                    </IconButton>
                </Tooltip>
            </div>
        }
    }

    /**
     * Alterne l'état de modification.
     */
    toggleModification(){
        this.modifying = !this.modifying;
        this.forceUpdate();
    }

    /**
     * Crée un bloc d'information vide.
     */
    async createInformationBlock(){
        let lastOrder = 0;
        this.state.informations.forEach(info => {
            if (info.order > lastOrder) {
                lastOrder = info.order;
            }
        })
        //Modèle de bloc d'informations vide
        const template : InformationBlockInfo = {
            id:0,
            enabled:false,
            title:"Titre à modifier",
            content:"Contenu à modifier",
            order:lastOrder+1
        }
        await InformationService.createInformationBlock(template);
        this.getInfos();
    }

    /**
     * Lorsque l'enfant a fait des modifications à la liste d'information, changer l'état ici aussi.
     * @param informations Liste d'informations
     */
    onUpdate(informations:InformationBlockInfo[]){
        this.setState({informations})
    }

    render(){
        let informations = this.state.informations.filter(info => info.enabled)
        let content = informations.map(info => {return info.content}).join("\n");
        return (
            <div data-testid="Information" className={styles.informationPage}>
                <Layout name={TEXTS.informations.title}>
                    {/* Boutons administrateurs */}
                    {this.generateButtons()}
                    {/* Affichage des informations */}
                    {!this.modifying && <MDContentViewer 
                    content={content}/>}
                    {/* Éditeur des informations */}
                    {this.modifying && <InformationsEditor update={this.onUpdate} informations={this.state.informations}/>}
                </Layout>
            </div>
        );
    }
}