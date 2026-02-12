/**
 * Mathieu Sévégny
 */
import { Grid, Paper } from '@mui/material';
import React from 'react';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import { TEXTS } from '../../lang/fr';
import ContactPerson from '../../types/sign-up/contact-person';
import { MAX_LENGTH_CONTACT_PERSON_NAME, MAX_LENGTH_EMAIL, REGEX } from '../../utils/constants';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import styles from "./../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";

interface TeamContactPersonProps {
    contactPerson:ContactPerson;
    number:number;
    handleChangeContact:(number:number,key:string,value:any) => void;
}


/**
 * Formulaire pour entrer une personne ressource
 */
export default class TeamContactPerson extends React.Component<TeamContactPersonProps> {
    /**
     * Vérification personnaliser
     */
     componentDidMount() {

        //  Vérfier la longeur du champs nom
        ValidatorForm.addValidationRule('maxLenghtFullName', (value) => {
        if (value.length > MAX_LENGTH_CONTACT_PERSON_NAME) {
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
    }
        
    //Permet d'enlever l'erreur des champs quand il respecte les critères
    componentWillUnmount() {
        // Retir l'erreur pour le champ nom
        ValidatorForm.removeValidationRule('maxLenghtFullName');
        // Retir l'erreur pour le champs adresse courriel
        ValidatorForm.removeValidationRule('maxLenghtEmail');
    }

    render(){
        return (
            <Paper elevation={8} className={`${styles.paddingPaper} ${styles.paddingPaperTop} ${styles.member}`}>
            <Paper
                    className={`${styles.subhead} ${styles.stack}`}
                >
                    <div className={styles.flexTitle}>
                        <h2>{TEXTS.signup.information.contactPerson.label1}
                            {this.props.number === 1 ? <>{TEXTS.signup.information.contactPerson.label2}</> : <>{TEXTS.signup.information.contactPerson.label3} {this.props.number}<sup>{"e"} </sup></>}
                            {TEXTS.signup.information.contactPerson.label4}
                        </h2>
                    </div>
                </Paper>
                    <Grid container spacing={2}>
                        <Grid item xs={12} md={12}>
                            {/* Champs pour le prénom et le nom de la personne ressource */}
                            <TextValidator
                                required
                                variant={INPUT_VARIANT}
                                label={TEXTS.signup.information.contactPerson.firtsLastName.label}
                                name={TEXTS.signup.firstName.label}
                                onChange={(event:any) => this.props.handleChangeContact(this.props.number,"fullName",event.target.value)}
                                value={this.props.contactPerson.fullName}
                                validators={['required', 'maxLenghtFullName']}
                                errorMessages={[TEXTS.signup.information.contactPerson.firtsLastName.error.required, 
                                    TEXTS.signup.information.contactPerson.firtsLastName.error.maximum]}
                                inputProps={{ maxLength: MAX_LENGTH_CONTACT_PERSON_NAME }}
                                fullWidth />
                            <p className={styles.alignRight}>{this.props.contactPerson.fullName.length} / {MAX_LENGTH_CONTACT_PERSON_NAME}</p>
                        </Grid>
                        <Grid item xs={12} md={12}>
                            {/* Champs pour l'adresse courriel de la personne ressource */}
                            <TextValidator
                                required
                                variant={INPUT_VARIANT}
                                label={TEXTS.signup.email.label}
                                onChange={(event:any) => this.props.handleChangeContact(this.props.number,"email",event.target.value)}
                                name={TEXTS.signup.email.label}
                                value={this.props.contactPerson.email}
                                validators={['required', 'matchRegexp:'+REGEX.CONTACT_PERSON, 'maxLenghtEmail']}
                                errorMessages={[TEXTS.signup.email.error.required, TEXTS.signup.email.errorContactPerson.invalide, TEXTS.signup.email.error.maximum]}
                                inputProps={{ maxLength: MAX_LENGTH_EMAIL }}
                                fullWidth />
                            <p className={styles.alignRight}>{this.props.contactPerson.email.length} / {MAX_LENGTH_EMAIL}</p>
                        </Grid>
                    </Grid>
                </Paper>
        );
    }
}
