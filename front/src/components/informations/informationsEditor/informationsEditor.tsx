import React from 'react';
import InformationService from '../../../api/informations/informationService';
import InformationBlockInfo from '../../../types/informations/informationBlockInfo';
import { isInArray, sortByNumber } from '../../../utils/utils';
import InformationBlock from '../informationsBlock/informationsBlock';


interface InformationsEditorProps{
    informations:InformationBlockInfo[];
    update:(informations:InformationBlockInfo[]) =>void;
}
interface InformationsEditorState{
    informations:InformationBlockInfo[];
}
/**
 * Composant permettant de modifier les blocs d'informations.
 * @author Mathieu Sévégny
 */
export default class InformationsEditor extends React.Component<InformationsEditorProps,InformationsEditorState> {
    constructor(props:InformationsEditorProps){
        super(props)

        this.state = {
            informations:this.props.informations
        }
        this.onModify = this.onModify.bind(this);
        this.onMove = this.onMove.bind(this);
        this.toggleVisibility = this.toggleVisibility.bind(this);
        
        this.verifyOrder();
    }
    /**
     * Vérifie l'ordre des blocs pour que les valeurs soient valides.
     * Si un bloc a été ajouté directement avec la bd, il peut y avoir des pareils.
     */
    async verifyOrder(){
        const array = this.props.informations.sort((a,b) => sortByNumber(a.order,b.order));
        let supposedOrder = 1;

        for (let i = 0; i < array.length; i++) {
            const element = array[i];
            if (element.order !== supposedOrder){
                element.order = supposedOrder;
                await InformationService.modifyOrderInformationBlock(element);
            }
            supposedOrder++;
        }
        this.setState({informations:array})
    }
    componentDidUpdate(){
        if (this.state.informations.length !== this.props.informations.length){
            //Ajoute le ou les blocs qui ne sont pas déjà dans le state. (Ajout de bloc d'informations)
            let array = this.state.informations;
            for (let i = 0; i < this.props.informations.length; i++) {
                const element = this.props.informations[i];
                if (!isInArray(element,this.state.informations,"id")){
                    array.push(element);
                }
            }
            this.setState({informations:array})
        }
    }
    /**
     * Fonction lorsqu'un bloc d'information a été modifié ou supprimé.
     * @param id Identifiant du bloc.
     * @param infoBlock Nouveau contenu du bloc.
     */
    async onModify(id:number,infoBlock:InformationBlockInfo | null){
        if (!infoBlock){
            //Supprime le bloc d'informations
            for (let i = 0; i < this.props.informations.length; i++) {
                const element = this.props.informations[i];
                if (element.id === id){
                    await InformationService.deleteInformationBlock(element);
                    
                    //Retire le bloc de la liste de blocs.
                    let array = this.props.informations;
                    array.splice(i,1);
                    this.setState({informations:array})
                    this.props.update(array)
                    return;
                }
            }
        }
        else{
            //Modifie le bloc d'informations
            let informations = this.state.informations;
            await InformationService.modifyInformationBlock(infoBlock);
            for (let i = 0; i < informations.length; i++) {
                const element = informations[i];
                if (element.id === id){
                    informations[i] = infoBlock;
                    break;
                }
            }
            this.setState({informations:informations})
            this.props.update(informations)
        }
    }
    /**
     * Déplace un bloc d'information.
     * @param id Identifiant du bloc à déplacer.
     * @param movement Type du mouvement
     */
    async onMove(id:number,movement:"up"|"down"){
        let informations = this.state.informations.sort((a,b) => sortByNumber(a.order,b.order));
        let blockPosition;

        for (let i = 0; i < informations.length; i++) {
            const element = informations[i];
            if (element.id === id){
                blockPosition = i;
            }
        }
        //Si non trouvé, ne fait rien.
        if (blockPosition === undefined)return;

        //Trouve la position de l'autre bloc à partir du mouvement voulu.
        let otherBlockPosition = blockPosition + (movement === "up" ? -1 : 1);

        //Échange les positions des deux blocs
        let temp = informations[blockPosition].order;
        informations[blockPosition].order = informations[otherBlockPosition].order
        informations[otherBlockPosition].order = temp;

        //Appelle l'API pour modifier les positions
        await InformationService.modifyOrderInformationBlock(informations[blockPosition])
        await InformationService.modifyOrderInformationBlock(informations[otherBlockPosition])

        this.setState({informations:informations})
    }
    /**
     * Génère les blocs d'informations.
     */
    generateInfoBlocks(){
        let elements = [];
        const infoArray = this.state.informations.sort((a,b) => sortByNumber(a.order,b.order));
        for (let i = 0; i < infoArray.length; i++) {
            const element = infoArray[i];
            //Vérifie la position de l'élément dans la liste. (Pour les boutons de modification de l'ordre)
            const isAtTop = i === 0;
            const isAtBottom = i === infoArray.length - 1;

            elements.push(<InformationBlock 
                isAtBottom={isAtBottom} 
                isAtTop={isAtTop} 
                onMove={this.onMove} 
                key={String(element.id)} 
                toggleVisibility={this.toggleVisibility}
                informationBlock={element} 
                onModify={this.onModify}/>)
        }
        return elements;
    }
    /**
     * Active ou désactive l'affichage du bloc d'informations.
     * @param id Identifiant du bloc.
     * @param visible Nouvel état de visibilité.
     */
    async toggleVisibility(id:number,visible:boolean){
        let array = this.state.informations;
        let element;
        for (let i = 0; i < array.length; i++) {
            if (array[i].id === id){
                array[i].enabled = visible;
                element = array[i];
                break;
            }
        }
        //Si il n'a pas trouvé l'élément ne fait rien.
        if (!element) return;

        await InformationService.modifyInformationBlock(element);

        this.setState({informations:array})
    }
    
    render(){
        return (
            <>
            {this.generateInfoBlocks()}
            </>
        );
    }
}