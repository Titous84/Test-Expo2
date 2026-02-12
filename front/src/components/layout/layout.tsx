import { Grid, Paper } from "@mui/material";
import React from "react";
import styles from "./layout.module.css";

interface LayoutProps{
    name:string,
    children:any,
    /**
     * Si défini, désactive l'affichage par défaut en tant que container
     * 
     * (Espace vide sur les cotés)
     */
    isNotContainer?:boolean
}
export default class Layout extends React.Component<LayoutProps>{
    public render() {
        return (
            <Grid container={this.props.isNotContainer == undefined} columns={12}>
                <Grid item xs={0} md={2}></Grid>
                <Grid item xs={12} md={8}>
                    <Paper elevation={8} className={`${styles.paddingPaper} ${styles.paddingPaperTop}`}>
                        <Paper
                            className={styles.subhead}
                        >
                            <h2>{this.props.name}</h2>
                        </Paper>
                        {this.props.children}
                    </Paper>
                </Grid>
                <Grid item xs={0} md={2}></Grid>
            </Grid>
        )
    }
}