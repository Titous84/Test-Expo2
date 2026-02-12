/**
 * Jean-Philippe Bourassa, basé sur le travail de Tristan Lafontaine
 */
import React from 'react';
import { ValidatorForm } from 'react-material-ui-form-validator'
import Judge from '../../types/judge';
import { EMPTY_STRING, MAX_LENGTH_EMAIL, MAX_LENGTH_FIRST_NAME, MAX_LENGTH_LAST_NAME } from '../../utils/constants';
import { Grid, Paper, FormControl, FormLabel, FormControlLabel, RadioGroup, Radio, Box } from '@mui/material';
import styles from "./../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";
import { TextValidator } from 'react-material-ui-form-validator'
import { SelectValidator } from 'react-material-ui-form-validator'
import { INPUT_VARIANT } from '../../utils/muiConstants';
import { TEXTS } from '../../lang/fr';
import Category from '../../types/sign-up/category';
import MenuItem from '@mui/material/MenuItem';
import { createRandomKey } from '../../utils/utils';

/**
 *  Composant inscription des juges
 */

interface JudgeFormProps {
    judge: Judge;
    handleChangeForm: (event: any, key: string) => void;
    categories: Category[];
}

export default class JudgeForm extends React.Component<JudgeFormProps> {
    /**
     * Vérification personnalisée
     * @author Jean-Philippe Bourassa
     * @author Etienne Nadeau
     */
    componentDidMount() {

        //  Vérfier la longeur du champs nom famille
        ValidatorForm.addValidationRule('maxLenghtLastName', (value) => {
            if (value.length > MAX_LENGTH_LAST_NAME) {
                return false;
            }
            return true;
        });
        //  Vérifier si le champ nom famille est vide
        ValidatorForm.addValidationRule('emptyLastName', (value) => {
            if (value == EMPTY_STRING) {
                return false;
            }
            return true;
        });

        //  Vérifier la longeur du champs prénom
        ValidatorForm.addValidationRule('maxLenghtFirstName', (value) => {
            if (value.length > MAX_LENGTH_FIRST_NAME) {
                return false;
            }
            return true;
        });

        //  Vérifier si le champ prénom est vide
        ValidatorForm.addValidationRule('emptyFirstName', (value) => {
            if (value == EMPTY_STRING) {
                return false;
            }
            return true;
        });

        //  Vérifier la longueur du champ adresse courriel
        ValidatorForm.addValidationRule('maxLenghtEmail', (value) => {
            if (value.length > MAX_LENGTH_EMAIL) {
                return false;
            }
            return true;
        });
        //  Vérifier si le champ adresse courriel est vide
        ValidatorForm.addValidationRule('emptyEmail', (value) => {
            if (value == EMPTY_STRING) {
                return false;
            }
            return true;
        });
    }


    /**
     * @author Jean-Philippe Bourassa
     * @author Etienne Nadeau
     * Permet d'enlever l'erreur des champs quand ils respectent les critères
     */
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse courriel
        ValidatorForm.removeValidationRule('maxLenghtEmail');
        ValidatorForm.removeValidationRule('emptyEmail');
        // Retir l'erreur pour le champs prénom
        ValidatorForm.removeValidationRule('maxLenghtFirstName');
        ValidatorForm.removeValidationRule('emptyFirstName');
        // Retir l'erreur pour le champs nom famille
        ValidatorForm.removeValidationRule('maxLenghtLastName');
        ValidatorForm.removeValidationRule('emptyLastName');
        // Retir l'erreur pour le champs consentement
        ValidatorForm.removeValidationRule('picture_consent');

    }
    /**
    * @author Jean-Philippe Bourassa
    * @author Etienne Nadeau
    * @returns {JSX.Element} - Le composant JSX du formulaire d'inscription du juge.
    */
    render() {
        return (
            <Paper elevation={8} className={`${styles.paddingPaper} ${styles.paddingPaperTop}`}>
                <Paper
                    className={`${styles.subhead} ${styles.stack}`}
                >
                    <h2>{TEXTS.signUpJudge.title}</h2>
                </Paper>
                <Box display="flex" flexWrap="wrap" gap={2}>
                    <Box width={{ xs: '100%', md: '100%' }} p={1}>
                        <TextValidator
                            required
                            variant={INPUT_VARIANT}
                            label={TEXTS.signup.firstName.label}
                            name={TEXTS.signup.firstName.label}
                            onChange={(event: any) => this.props.handleChangeForm(event.target.value, "firstName")}
                            value={this.props.judge.firstName}
                            validators={['required', 'maxLenghtFirstName', 'emptyFirstName']}
                            errorMessages={[TEXTS.signup.firstName.error.required, TEXTS.signup.firstName.error.maximum, TEXTS.signup.firstName.error.empty]}
                            inputProps={{ maxLength: MAX_LENGTH_FIRST_NAME + 1 }}
                            fullWidth />
                        <p className={styles.alignRight}>{this.props.judge.firstName.length} / {MAX_LENGTH_FIRST_NAME}</p>
                    </Box>
                    <Box width={{ xs: '100%', md: '100%' }} p={1}>
                        <TextValidator
                            required
                            variant={INPUT_VARIANT}
                            label={TEXTS.signup.lastName.label}
                            name={TEXTS.signup.lastName.label}
                            onChange={(event: any) => this.props.handleChangeForm(event.target.value, "lastName")}
                            value={this.props.judge.lastName}
                            validators={['required', 'maxLenghtLastName', 'emptyLastName']}
                            errorMessages={[TEXTS.signup.lastName.error.required, TEXTS.signup.lastName.error.maximum, TEXTS.signup.lastName.error.empty]}
                            inputProps={{ maxLength: MAX_LENGTH_LAST_NAME + 1 }}
                            fullWidth />
                        <p className={styles.alignRight}>{this.props.judge.lastName.length} / {MAX_LENGTH_LAST_NAME}</p>
                    </Box>
                    <Box width={{ xs: '100%', md: '100%' }} p={1}>
                        <TextValidator
                            required
                            variant={INPUT_VARIANT}
                            label={TEXTS.signup.email.label}
                            onChange={(event: any) => this.props.handleChangeForm(event.target.value, "email")}
                            name={TEXTS.signup.email.label}
                            value={this.props.judge.email}
                            validators={['required', 'isEmail', 'maxLenghtEmail', 'emptyEmail']}
                            errorMessages={[TEXTS.signup.email.error.required, TEXTS.signup.email.error.invalide, TEXTS.signup.email.error.maximum, TEXTS.signup.email.error.empty]}
                            inputProps={{ maxLength: MAX_LENGTH_EMAIL + 1 }}
                            fullWidth />
                        <p className={styles.alignRight}>{this.props.judge.email.length} / {MAX_LENGTH_EMAIL}</p>
                    </Box>
                    <Box width={{ xs: '100%', md: '100%' }} p={1}>
                        <SelectValidator
                            name={TEXTS.signup.information.category.label}
                            label={TEXTS.signup.information.category.label}
                            className={styles.widthCategory}
                            required
                            value={this.props.judge.category}
                            inputProps={{
                                name: this.props.judge.category,
                                id: 'uncontrolled-native',
                            }}
                            onChange={(event: any) => this.props.handleChangeForm(event.target.value, "category")}
                            validators={['required']}
                            errorMessages={[TEXTS.signup.information.category.error.required]}
                        >
                            {
                                this.props.categories.map((category) =>
                                    <MenuItem key={createRandomKey()} value={category.name}>{category.name}</MenuItem>
                                )
                            }
                        </SelectValidator>
                    </Box>
                    <Box width={{ xs: '100%', md: '100%' }} p={1}>
                        <FormControl>
                            <FormLabel id="demo-controlled-radio-buttons-group">{TEXTS.signup.pictureConsent.label}</FormLabel>
                            <RadioGroup
                                name="controlled-radio-buttons-group"
                                value={this.props.judge.pictureConsent}

                                onChange={(event: any) => {
                                    this.props.handleChangeForm(event.target.value, "pictureConsent")
                                }}
                            >
                                <FormControlLabel value={true} control={<Radio />} label={TEXTS.signup.pictureConsent.yes} />
                                <FormControlLabel value={false} control={<Radio />} label={TEXTS.signup.pictureConsent.no} />
                            </RadioGroup>
                        </FormControl>
                    </Box>
                </Box>
            </Paper>
        );
    }
}