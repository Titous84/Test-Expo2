import React from 'react';
import MDContentEditor from '../../markdown/mdContentEditor';
import MDContentViewer from '../../markdown/mdContentViewer';
import InformationBlockInfo from '../../../types/informations/informationBlockInfo';
import styles from "./informationsBlock.module.css";
import { IconButton, TextField, Tooltip } from '@mui/material';
import { Cancel, Delete, Edit, Save } from '@mui/icons-material';
import ArrowDownwardIcon from '@mui/icons-material/ArrowDownward';
import ArrowUpwardIcon from '@mui/icons-material/ArrowUpward';
import VisibilityIcon from '@mui/icons-material/Visibility';
import VisibilityOffIcon from '@mui/icons-material/VisibilityOff';
import { TEXTS } from '../../../lang/fr';
import { INPUT_VARIANT } from '../../../utils/muiConstants';

interface InformationBlockProps{
    informationBlock:InformationBlockInfo;
    onModify:(id:number,infoBlock:InformationBlockInfo | null) => void;
    onMove:(id:number,movement:"up"|"down")=>void;
    toggleVisibility:(id:number,visible:boolean)=>void;
    isAtTop:boolean;
    isAtBottom:boolean;
}
interface InformationBlockState{
    isModifying:boolean;
    currentValue:InformationBlockInfo | null;
}
/**
 * Composant correspondant à un bloc d'informations.
 * @author Mathieu Sévégny
 */
export default class InformationBlock extends React.Component<InformationBlockProps, InformationBlockState> {
    constructor(props:InformationBlockProps){
        super(props)
        this.state = {
            isModifying:false,
            currentValue:null
        }
    }
    /**
     * Permet la modification des champs « content » et « titre ».
     * @param newValue Nouvelle valeur
     * @param field Champ à modifier
     */
    onModify(newValue:string, field:"content" | "title"){
        let infoBlock = this.state.currentValue!!;
        infoBlock[field] = newValue;
        this.setState({currentValue:infoBlock})
    }
    /**
     * Sauvegarde les changements apportés.
     */
    onSave(){
        const value = this.state.currentValue;
        this.props.onModify(value!!.id,value);
        this.setState({isModifying:false})
    }
    /**
     * Annule les changements apportés.
     */
    onCancel(){
        //Vérifie la présence de modifications.
        if ((this.props.informationBlock.content !== this.state.currentValue!!.content) || 
        (this.props.informationBlock.title !== this.state.currentValue!!.title)){
            // eslint-disable-next-line no-restricted-globals
            const response = confirm(TEXTS.informations.confirm.cancel);

            if (!response) return;
        }

        this.setState({isModifying:false,currentValue:this.props.informationBlock})
    }
    /**
     * Supprime le bloc d'information. Demande aussi une confirmation.
     */
    onDelete(){
        // eslint-disable-next-line no-restricted-globals
        const response = confirm(TEXTS.informations.confirm.delete);

        if (!response) return;

        this.props.onModify(this.props.informationBlock.id,null)
    }
    /**
     * Génère les flèches appropriés pour le déplacement.
     */
    generateArrowsButton(){
        let elementsToShow = []

        if (!this.props.isAtTop){
            elementsToShow.push(
            <IconButton aria-label="monter" component="span"
            onClick={() => this.props.onMove(this.props.informationBlock.id,"up")}>
                <ArrowUpwardIcon/>
            </IconButton>
            )
        }
        if (!this.props.isAtBottom){
            elementsToShow.push(
                <IconButton aria-label="descendre" component="span"
                onClick={() => this.props.onMove(this.props.informationBlock.id,"down")}>
                    <ArrowDownwardIcon  />
                </IconButton>
                )
        }
        return elementsToShow;
    }
    /**
     * Génère le bouton de visibilité approprié.
     */
    generateVisibilityButton(){
        if (this.props.informationBlock.enabled){
            return (<Tooltip id="button-report" title={TEXTS.informations.buttons.hide}>
                <IconButton aria-label="afficher" component="span"
            onClick={() => this.props.toggleVisibility(this.props.informationBlock.id,false)}>
                        <VisibilityIcon />
                    </IconButton></Tooltip>)
        }
        return (<Tooltip id="button-report" title={TEXTS.informations.buttons.show}>
            <IconButton aria-label="afficher" component="span" 
        onClick={() => this.props.toggleVisibility(this.props.informationBlock.id,true)}>
                        <VisibilityOffIcon />
                    </IconButton></Tooltip>)
    }
    /**
     * Génère les boutons appropriés.
     */
    generateButtons(){
        if (this.state.isModifying){
            return (
            <div className={styles.iconDiv}>
                <Tooltip id="button-report" title={TEXTS.informations.buttons.saveBlock}>
                    <IconButton aria-label="sauvegarder" component="span" onClick={() => this.onSave()}>
                        <Save />
                    </IconButton>
                </Tooltip>
                <Tooltip id="button-report" title={TEXTS.informations.buttons.cancel}>
                    <IconButton aria-label="annuler" component="span" onClick={() => this.onCancel()}>
                        <Cancel />
                    </IconButton>
                </Tooltip>
                {this.generateVisibilityButton()}
                {this.generateArrowsButton()}
                <Tooltip id="button-report" title={TEXTS.informations.buttons.delete}>
                    <IconButton aria-label="suppression" component="span" onClick={() => this.onDelete()}>
                        <Delete />
                    </IconButton>
                </Tooltip>
            </div>)
        }
        else{
            return (
                <div className={styles.iconDiv}>
                    <IconButton 
                    aria-label="modifier" 
                    component="span" 
                    onClick={() => this.setState({isModifying:true,currentValue:{...this.props.informationBlock}})}>
                        <Edit />
                    </IconButton>
                    {this.generateVisibilityButton()}
                    {this.generateArrowsButton()}
                    <Tooltip id="button-report" title={TEXTS.informations.buttons.delete}>
                        <IconButton aria-label="suppression" component="span" onClick={() => this.onDelete()}>
                            <Delete />
                        </IconButton>
                    </Tooltip>
                </div>)
        }
    }
    render(){
        //Affichage pour voir le contenu du bloc
        if (!this.state.isModifying){
            return (
            <div className={styles.infoBlock}>
                <div className={styles.titleAndIcons}>
                    <h3 className={styles.title}>{this.props.informationBlock.title}</h3>
                    {this.generateButtons()}
                </div>
                <MDContentViewer content={this.props.informationBlock.content}/>
            </div>)
        }
        //Affichage pour éditer le bloc
        return (
            <div className={styles.infoBlock}>
                <div className={styles.titleAndIcons}>
                    <TextField variant={INPUT_VARIANT} className={styles.title} value={this.state.currentValue!!.title} onChange={e =>{this.onModify(e.target.value,"title")}}/>
                    {this.generateButtons()}
                </div>
                <MDContentEditor 
                    content={this.state.currentValue!!.content} 
                    onChange={value => {
                        this.onModify(value!!,"content")
                    }}
                />
            </div>
        );
    }
}