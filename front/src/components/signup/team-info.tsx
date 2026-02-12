/**
 * @author Tristan Lafontaine
 */
import React from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator'
import { MAX_LENGTH_DESCRIPTION_STAND, MAX_LENGTH_TITLE_STAND } from '../../utils/constants';
import { INPUT_VARIANT } from '../../utils/muiConstants';
import { TEXTS } from '../../lang/fr';
import Grid from '@mui/material/Grid';
import Paper from '@mui/material/Paper';
import TeamInfo from '../../types/sign-up/team-info';
import Radio from '@mui/material/Radio';
import RadioGroup from '@mui/material/RadioGroup';
import FormControlLabel from '@mui/material/FormControlLabel';
import InputLabel from '@mui/material/InputLabel';
import MenuItem from '@mui/material/MenuItem';
import FormLabel from '@mui/material/FormLabel';
import { createRandomKey } from '../../utils/utils';
import BasicPopover from '../help/PopOver';
import Category from '../../types/sign-up/category';
import styles from "./../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";

/**
 *  Formulaire d'inscription pour les membres
 */

 interface TeamInformationProps{
    teamInfo:TeamInfo
    handleChangeForm:(event:any, key:string)=>void
    categories:Category[];
 }

export default class TeamInformation extends React.Component<TeamInformationProps> {

    /**
    * Vérification personnaliser
    */
    componentDidMount() {
        //  Vérfier la longeur du champs Titre du stand
        ValidatorForm.addValidationRule('maxLenghtTitleStand', (value) => {
            if (value.length > MAX_LENGTH_TITLE_STAND) {
                return false;
            }
                return true;
        });
        //  Vérifier la longeur du champs Description du stand
        ValidatorForm.addValidationRule('maxLenghtDescriptionStand', (value) => {
            if (value.length > MAX_LENGTH_DESCRIPTION_STAND) {
                return false;
            }
            return true;
        });
    }
            
    //Permet d'enlever l'erreur des champs quand il respecte les critères
    componentWillUnmount() {
        // Retir l'erreur pour le champ adresse titre du stand
        ValidatorForm.removeValidationRule('maxLenghtTitleStand');
        // Retir l'erreur pour le champs description du stand
        ValidatorForm.removeValidationRule('maxLenghtDescriptionStand');
    }

    render(){
        return (
          <Paper elevation={8} className={`${styles.paddingPaper} ${styles.paddingPaperTop}`}>
            <Paper
                    className={`${styles.subhead} ${styles.stack}`}
                >
                    <h2>{TEXTS.signup.information.title}</h2>
                </Paper>
                    <Grid container spacing={2}>
                        <Grid item xs={12} md={12}>
                            {/* Champs pour le prénom de la personne ressource */}
                            <TextValidator
                                required
                                variant={INPUT_VARIANT}
                                label={TEXTS.signup.information.titleStand.label}
                                name={TEXTS.signup.information.titleStand.label}
                                onChange={(event:any) => {
                                    this.props.handleChangeForm(event.target.value,"title")
                                }}
                                value={this.props.teamInfo.title}
                                validators={['required', 'maxLenghtTitleStand']}
                                errorMessages={[TEXTS.signup.information.titleStand.error.required, TEXTS.signup.information.titleStand.error.maximum]}
                                inputProps={{ maxLength: MAX_LENGTH_TITLE_STAND }}
                                fullWidth />
                            <p className={styles.alignLeft}>{TEXTS.signup.information.titleStand.text}</p>
                            <p className={styles.teamInfoMaxLenght}>{this.props.teamInfo.title.length} / {MAX_LENGTH_TITLE_STAND}</p>
                        </Grid>
                        <Grid item xs={12} md={12}>
                            {/* Champs pour le nom de la personne ressource */}
                            <TextValidator
                                required
                                variant={INPUT_VARIANT}
                                label={TEXTS.signup.information.descriptionStand.label}
                                name={TEXTS.signup.information.descriptionStand.label}
                                onChange={(event:any) => {
                                    this.props.handleChangeForm(event.target.value,"description")
                                }}
                                value={this.props.teamInfo.description}
                                validators={['required', 'maxLenghtDescriptionStand']}
                                errorMessages={[TEXTS.signup.information.descriptionStand.error.required, TEXTS.signup.information.descriptionStand.error.maximum]}
                                inputProps={{ maxLength: MAX_LENGTH_DESCRIPTION_STAND }}
                                fullWidth />
                            <p className={styles.alignLeft}>{TEXTS.signup.information.descriptionStand.text}</p>
                            <p className={styles.teamInfoMaxLenght}>{this.props.teamInfo.description.length} / {MAX_LENGTH_DESCRIPTION_STAND}</p>
                        </Grid>
                    </Grid>
                    <Grid container spacing={2}>
                        {/* Champs pour de la categorie de la personne ressource */}
                        <Grid item xs={12} md={6}>
                            <div className={styles.flex}>
                                <InputLabel id="select-label">{TEXTS.signup.information.category.label}</InputLabel>
                                <div className={styles.helpPosition}>
                                    <BasicPopover text={TEXTS.signup.information.category.help} color={"black"}/>
                                </div>
                            </div>
                            
                            <SelectValidator
                                name="category"
                                className={styles.widthCategory}
                                required
                                id="simple-select"
                                value={this.props.teamInfo.category}
                                inputProps={{
                                    name: this.props.teamInfo.category,
                                    id: 'uncontrolled-native',
                                  }}
                                onChange={(event:any) => {
                                    
                                    this.props.handleChangeForm(event.target.value,"category")
                                }}
                                validators={['required']}
                                errorMessages={[TEXTS.signup.information.category.error.required]}
                            >
                                {
                                    this.props.categories
                                    .sort((a: Category, b: Category) => a.name.localeCompare(b.name))
                                    .map((category) =>
                                        <MenuItem key={createRandomKey()} value={category.name}>{category.name}</MenuItem>
                                    )
                                }
                            </SelectValidator>
                        </Grid>
                        <Grid item xs={12} md={6}>
                            {/* Radio boutton pour l'année de la personne ressource */}
                            <div className={styles.flex}>
                                <FormLabel>{TEXTS.signup.information.schoolYear.label}</FormLabel>
                                <div className={styles.helpPosition}>
                                    <BasicPopover text={TEXTS.signup.information.schoolYear.help} color="black"/>
                                </div>
                            </div>
                            <RadioGroup
                                aria-labelledby="demo-controlled-radio-buttons-group"
                                name="controlled-radio-buttons-group"
                                value={this.props.teamInfo.year}
                                onChange={(event:any) => {
                                    this.props.handleChangeForm(event.target.value,"year")
                                }}
                            >
                                <FormControlLabel value={TEXTS.signup.information.schoolYear.checkboxOne} control={<Radio />} label={TEXTS.signup.information.schoolYear.checkboxOne}/>
                                <FormControlLabel value={TEXTS.signup.information.schoolYear.checkboxTwo} control={<Radio />} label={TEXTS.signup.information.schoolYear.checkboxTwo} />
                            </RadioGroup>
                        </Grid>
                    </Grid>
                </Paper>
        );
    }
}