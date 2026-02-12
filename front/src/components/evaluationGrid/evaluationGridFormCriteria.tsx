import React from 'react';
import { IEvaluationGridCriteria } from '../../types/evaluationGrid/IEvaluationGridCriteria';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import {Grid, TextField } from '@mui/material';
import ButtonExposat from '../button/button-exposat';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import { TEXTS } from '../../lang/fr';

interface EvaluationGridFormCriteriaProps {
    criteria: IEvaluationGridCriteria;
    sectionPosition: number;
    criteriaPosition: number;
    removeCriteria: (sectionPosition:number, criteriaPosition:number) => void;
    handleChangeCriteria: (key:string,value:any,sectionPosition:number, criteriaPosition:number) => void;
    handleChangeValue: (key:string,value:any,sectionPosition:number, criteriaPosition:number) => void;
}

/**
 * Composant de formulaire pour un critère de grille d'évaluation
 * @author Raphaël Boisvert
 * @author Thomas-Gabriel Paquin
 */

/**
 * Vérifie si la valeur est un nombre valide (entre 1 et 100)
 * @author Thomas-Gabriel Paquin
 * @param value Valeur à vérifier
 * @returns true si la valeur est un nombre valide, false sinon
 */
function isValidNumber(value: number) {
    return !isNaN(value) && value >= 1 && value <= 100;
}

/**
 * Vérifie si la valeur est un nom valide (entre 1 et 255 caractères)
 * @author Thomas-Gabriel Paquin
 * @param value Valeur à vérifier
 * @returns true si la valeur est un nom valide, false autrement.
 */
function isValidName(value: string) {
    return !isNaN(value.length) && value.length >= 1 && value.length <= 255;
}

export default class EvaluationGridFormCriteria extends React.Component<EvaluationGridFormCriteriaProps> {
    componentDidMount(): void {
        ValidatorForm.addValidationRule('maxLengthCriteriaName', (value) => {
            return value.length <= 255;
        });
    }

    componentWillUnmount(): void {
        ValidatorForm.removeValidationRule('maxLengthCriteriaName');
    }

    render() {
        return (
            <Grid container spacing={2}>
                <Grid item xs={9} md={9}>
                    <TextValidator
                        required
                        variant={INPUT_VARIANT}
                        label="Nom du critère"
                        name="name"
                        fullWidth
                        onChange={(event:any) => this.props.handleChangeCriteria('name', event.target.value, this.props.sectionPosition, this.props.criteriaPosition)}
                        value={this.props.criteria.name}
                        validators={['required', 'maxLengthCriteriaName']}
                        error={!isValidName(this.props.criteria.name)}
                        helperText={!isValidName(this.props.criteria.name) && TEXTS.evaluationGridForm.criteriaName.error.required}
                        inputProps={{ maxLength: 255 }}
                    />
                    <p>{this.props.criteria.name.length} / 255</p>

                    <Grid item xs={9} md={9}>
                        <TextField
                        name="max_value"
                        label="pondération"
                        type="number"
                        InputProps={{ 
                            inputProps: { 
                                min: 1, 
                                max: 100,
                                step: 1,
                            } }}
                        value={this.props.criteria.max_value}
                        InputLabelProps={{
                            shrink: true,
                        }}
                        onChange={(event:any) => this.props.handleChangeValue('max_value', event.target.value, this.props.sectionPosition, this.props.criteriaPosition)}
                        error={!isValidNumber(this.props.criteria.max_value)}
                        helperText={!isValidNumber(this.props.criteria.max_value) && TEXTS.evaluationGridForm.criteriaMaxValue.error.maximum}
                        /><br/><br/><br/><br/>
                    </Grid>
                </Grid>
                <Grid item xs={3} md={3}>
                    <ButtonExposat onClick={() => this.props.removeCriteria(this.props.sectionPosition, this.props.criteria.position)} children={"- Supprimer le critère"} />
                </Grid>
            </Grid>
        );
    }
}