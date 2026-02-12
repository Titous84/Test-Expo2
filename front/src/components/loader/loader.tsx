import { CircularProgress } from '@mui/material';
import React from 'react';
import style from "./loader.module.css"

interface LoaderProps{

}

export default class Loader extends React.Component<LoaderProps> {
    constructor(props:LoaderProps){
        super(props)
        this.state = {
            
        }
    }
    render(){
        return (
            <div className={style.loaderDiv}>
                <CircularProgress />
            </div>
        );
    }
}