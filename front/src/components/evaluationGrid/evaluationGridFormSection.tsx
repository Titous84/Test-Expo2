import React from 'react';
import { IEvaluationGridSection } from '../../types/evaluationGrid/IEvaluationGridSection';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import Layout from '../layout/layout';
import {Grid } from '@mui/material';
import ButtonExposat from '../button/button-exposat';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import EvaluationGridFormCriteria from './evaluationGridFormCriteria';
import { TEXTS } from '../../lang/fr';

interface EvaluationGridFormSectionProps {
    section: IEvaluationGridSection;
    sectionPosition: number;
    removeSection: (sectionPosition:number) => void;
    addCriteria: (sectionPosition:number) => void;
    removeCriteria: (sectionPosition:number,criteriaPosition:number) => void;
    handleChangeSection: (key:string,value:any,sectionPosition:number) => void;
    handleChangeCriteria: (key:string,value:any,sectionPosition:number,criteriaPosition:number) => void;
    handleChangeValue: (key:string,value:any,sectionPosition:number,criteriaPosition:number) => void;
}

function isValidName(value: string) {
    return !isNaN(value.length) && value.length >= 1 && value.length <= 255;
}

/**
 * Composant de formulaire pour une section de grille d'évaluation
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 */
export default class EvaluationGridFormSection extends React.Component<EvaluationGridFormSectionProps> {
    componentDidMount(): void {
        ValidatorForm.addValidationRule('maxLengthSectionName', (value) => {
            return value.length <= 255;
        });
    }

    componentWillUnmount(): void {
        ValidatorForm.removeValidationRule('maxLengthSectionName');
    }

    /**
     * Génère les formulaires de critères pour une section
     */
    generateCriteriaForms() {
        let criteriaForms = [];
        for (let i = 0; i < this.props.section.criterias.length; i++) {
            criteriaForms.push(
                <Grid item xs={12} md={12}>
                    <EvaluationGridFormCriteria 
                    criteria={this.props.section.criterias[i]} 
                    sectionPosition={this.props.sectionPosition} 
                    criteriaPosition={i} 
                    handleChangeCriteria= {this.props.handleChangeCriteria} 
                    removeCriteria={this.props.removeCriteria} 
                    handleChangeValue={this.props.handleChangeValue} />
                </Grid>
            );
        }
        return criteriaForms;
    }

    render() {
        return (
            <Layout name={`Section ${this.props.sectionPosition}`}>
                <Grid container spacing={2}>
                    <Grid item xs={9} md={9}>
                        <TextValidator
                            required
                            variant={INPUT_VARIANT}
                            label="Nom de la section"
                            name="name"
                            fullWidth
                            onChange={(event:any) => this.props.handleChangeSection('name', event.target.value, this.props.sectionPosition)}
                            value={this.props.section.name}
                            validators={['required', 'maxLengthSectionName']}
                            error={!isValidName(this.props.section.name)}
                            helperText={!isValidName(this.props.section.name) && TEXTS.evaluationGridForm.sectionName.error.required}
                            inputProps={{ maxLength: 255 }}
                        />
                        <p>{this.props.section.name.length} / 255</p>
                    </Grid>
                    <Grid item xs={3} md={3}>
                        <ButtonExposat onClick={() => this.props.removeSection(this.props.sectionPosition)} children={"- Supprimer la section"} />
                    </Grid>
                    <Grid item xs={12} md={12}>
                        <p>Critères</p>
                    </Grid>
                    <Grid item xs={12} md={12}>
                        {this.generateCriteriaForms()}
                    </Grid>
                    <Grid item xs={12} md={12}>
                        <ButtonExposat onClick={() => this.props.addCriteria(this.props.sectionPosition)} children={"+ Ajouter un critère"} />
                    </Grid>
                </Grid>
            </Layout>
        );
    }
}