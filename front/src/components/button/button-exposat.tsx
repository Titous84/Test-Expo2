import React from 'react';
import styles from "./button-exposat.module.css"
import { Button } from "@mui/material";
import { INPUT_VARIANT } from "../../utils/muiConstants";

interface ButtonExposatProps{
    children:any;
    className?:string;
    onClick?:()=>void;
    disabled?:boolean;
    startIcon?:any;
    contained?:boolean;
}

/**
 * @author Christopher Boisvert
 *  Bouton standardisé pour les pages d'ExpoSAT.
 */
export default class ButtonExposat extends React.Component<ButtonExposatProps, {}> {
    
    constructor(props:ButtonExposatProps){
        super(props)
        this.state = {
            
        }
    }

    render(){
        return (
            <Button 
            variant={this.props.contained ? "contained" : INPUT_VARIANT} 
            className={styles.bouton + " " + this.props.className} 
            onClick={this.props.onClick} 
            disabled={this.props.disabled}
            component={this.props.contained ? "label" : "button"}
            startIcon={this.props.startIcon}
            >{this.props.children}</Button>
        );
    }
}