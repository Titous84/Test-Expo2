import React from "react";
import Alert from '@mui/material/Alert';
import AlertTitle from '@mui/material/AlertTitle';
import styles from "../../pages/ParticipantRegistration/ParticipantRegistrationPage.module.css";

type TYPEALERT = "error" | "warning" | "info" | "success"

interface AlertPros{
    typeAlert: TYPEALERT;
    errorMessage:string
    titleAlert:string
}

export default class AlertComposant extends React.Component<AlertPros> {
   render() {
       return (
        <Alert className={styles.alertMargin} severity={this.props.typeAlert}>
        <AlertTitle>{this.props.titleAlert}</AlertTitle>
             {this.props.errorMessage}
      </Alert>
       )
   }
}