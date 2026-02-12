/**
 * Jean-Philippe Bourassa, basé sur le travail de Tristan Lafontaine
 */
 import React from 'react';
 import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
 import Judge from '../../types/judge';
 import { MAX_LENGTH_PWD } from '../../utils/constants';
 import { FormControl,FormControlLabel, FormLabel, Grid, InputLabel, MenuItem, Paper, Radio, RadioGroup } from '@mui/material';
 import styles from "./../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";
 import { INPUT_VARIANT } from '../../utils/muiConstants';
 import { TEXTS } from '../../lang/fr';
 import { createRandomKey } from '../../utils/utils';
 import BasicPopover from '../help/PopOver';
 import Category from '../../types/sign-up/category';
 
 /**
  *  Composant vérification des juges
  */
 
  interface JudgeFormProps{
      judge:Judge;
      handleChangeForm:(event:any, key:string)=>void;
      categories:Category[];
      firstname:string;
      lastname:string;
  }

 export default class JudgeVerificationForm extends React.Component<JudgeFormProps> {
     /**
      * Vérification personnalisée
      */
      componentDidMount() {
 
         //  Vérfier la longeur du champs mot de passe
         ValidatorForm.addValidationRule('maxLenghtPwd', (value) => {
         if (value.length > MAX_LENGTH_PWD) {
             return false;
         }
             return true;
         });

         ValidatorForm.addValidationRule('pwdMatch', (value) => {
            if (value !== this.props.judge.pwd){
                return false
            }
            return true;
         });
     }
         
     //Permet d'enlever l'erreur des champs quand ils respectent les critères
     componentWillUnmount() {
         // Retire l'erreur pour le champs mot de passe
         ValidatorForm.removeValidationRule('maxLenghtPwd');
     }
 
     render(){
         return (
             <Paper elevation={8} className={`${styles.paddingPaper} ${styles.paddingPaperTop}`}>
             <Paper
                     className={`${styles.subhead} ${styles.stack}`}
                 >
                     <h2>{this.props.firstname} {this.props.lastname}</h2>
                 </Paper>
                     <Grid container spacing={2}>
                         <Grid item xs={12} md={6}>
                             <TextValidator
                                 required
                                 variant={INPUT_VARIANT}
                                 label={TEXTS.signUpJudge.pwd.label}
                                 name={TEXTS.signUpJudge.pwd.label}
                                 onChange={(event:any) => this.props.handleChangeForm(event.target.value,"pwd")}
                                 value={this.props.judge.pwd}
                                 validators={['required', 'maxLenghtPwd']}
                                 errorMessages={[TEXTS.signUpJudge.pwd.error.required, TEXTS.signUpJudge.pwd.error.maximum]}
                                 inputProps={{ maxLength: MAX_LENGTH_PWD }}
                                 fullWidth />
                             <p className={styles.alignRight}>{this.props.judge.pwd.length} / {MAX_LENGTH_PWD}</p>
                         </Grid>
                         <Grid item xs={12} md={6}>
                             <TextValidator
                                 required
                                 variant={INPUT_VARIANT}
                                 label={TEXTS.signUpJudge.pwdconfirm.label}
                                 name={TEXTS.signUpJudge.pwdconfirm.label}
                                 onChange={(event:any) => this.props.handleChangeForm(event.target.value,"pwdconfirm")}
                                 value={this.props.judge.pwdconfirm}
                                 validators={['required', 'maxLenghtPwd', 'pwdMatch']}
                                 errorMessages={[TEXTS.signUpJudge.pwd.error.required, TEXTS.signUpJudge.pwd.error.maximum, TEXTS.signUpJudge.pwdconfirm.error.match]}
                                 inputProps={{ maxLength: MAX_LENGTH_PWD }}
                                 fullWidth />
                             <p className={styles.alignRight}>{this.props.judge.pwd.length} / {MAX_LENGTH_PWD}</p>
                         </Grid>
                         <Grid item xs={12} md={12}>
                         <FormControl>
                             <FormLabel id="demo-controlled-radio-buttons-group">{TEXTS.signup.pictureConsent.label}</FormLabel>
                             <RadioGroup
                                 name="controlled-radio-buttons-group"
                                 value={this.props.judge.pictureConsent}
                                 onChange={(event:any) => {
                                     this.props.handleChangeForm(event.target.value,"pictureConsent")
                                 }}
                             >
                                 <FormControlLabel value={true} control={<Radio />} label={TEXTS.signup.pictureConsent.yes} />
                                 <FormControlLabel value={false} control={<Radio />} label={TEXTS.signup.pictureConsent.no} />
                             </RadioGroup>
                             </FormControl>
                         </Grid>
                         {/* Champs pour de la categorie du juge */}
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
                                value={this.props.judge.category}
                                inputProps={{
                                    name: this.props.judge.category,
                                    id: 'uncontrolled-native',
                                  }}
                                onChange={(event:any) => {
                                    
                                    this.props.handleChangeForm(event.target.value,"category")
                                }}
                                validators={['required']}
                                errorMessages={[TEXTS.signup.information.category.error.required]}
                            >
                                {
                                    this.props.categories.map((category) => 
                                        <MenuItem key={createRandomKey()} value={category.name}>{category.name}</MenuItem>
                                    )
                                }
                            </SelectValidator>
                        </Grid>
                     </Grid>
                 </Paper>
         );
     }
 }