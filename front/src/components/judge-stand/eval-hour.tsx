import React from 'react';
import { Box, Button, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, Slider } from '@mui/material';
import { LocalizationProvider, TimePicker } from '@mui/x-date-pickers';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFnsV3';
import TimeSlots from '../../types/juge-stand/timeSlots';
import JugeStandService from '../../api/juge-stand/jugestandService';

interface EvalHourProps {
    hours: TimeSlots[];
    boolDialog: boolean;
    onChangeHour: (value: Date, index: number) => void;
    handleClose: () => void;
    handleHoursChange: (newHour: Date, interval: number) => void;
    onAddTimeSlot: (heureDepart: Date, interval: number) => void;
    onDeleteTimeSlot: () => void;
}

interface EvalHourState {
    startHour: Date,
    interval: number,
    hoursValids : boolean,
}

const marks = [
    {
        value: 5,
        label: "5"
    },
    {
        value: 60,
        label: "60"
    }
]

/**
 * selecteur d'heure pour l'evaluation
 * @author Christopher Boisvert,Alex Des Ruisseaux
 * @editor Xavier Houle
 */
export default class EvalHour extends React.Component<EvalHourProps, EvalHourState> {
    /**
     * Constructeur de la classe
     * @param props onchange du parent et heure de depart
     */
    constructor(props: EvalHourProps) {
        super(props);
        const defaultStartHour = props.hours.length > 0 ? props.hours[0].time : new Date("2022-01-01T09:00:00.000");
    
        this.state = {
            startHour: defaultStartHour,
            interval: 30,
            hoursValids: this.validateTime(defaultStartHour),
        };
    }

    /**
     * @author Xavier Houle
     * Change l'heure de départ et modifie le state.
     * Les heures de  passages sont changées selon l'heure de départ et l'intervalle
     * @param newHour La nouvelle heure de départ
     */
    handleChangeStartHour = (newHour: Date) => {
        this.setState({startHour: newHour, hoursValids: this.validateTime(newHour)});

        this.props.handleHoursChange(newHour, this.state.interval);
    }

    /**
     * @author Xavier Houle
     * Change l'intervalle et change le heures de passages selon l'heure de départ
     * et l'intervalle
     * @param _ 
     * @param newValue La nouvelle heure de passage
     */
    handleIntervalChange = (_: Event, newValue: number | number[]) => {
        this.setState({interval: Number(newValue)});

        this.props.handleHoursChange(this.state.startHour, Number(newValue));
    }

    /**
     * @author Xavier Houle
     * Modifie les heures de passages
     */
    async UpdateTimeSlots() {
        await JugeStandService.SaveAllTimeSlots(this.props.hours);
    }

    // s'occupe d'enregistrer les heures de passages
    handleEnregistrer = () => {
        this.UpdateTimeSlots();
    }

    // Vérifie que la date est valide
    validateTime = (time: Date): boolean => {
        return !isNaN(time.getTime());
    }

    render(){
        return (
            <Dialog 
                open={this.props.boolDialog} 
                onClose={this.props.handleClose}
            >
                <DialogTitle>Changer les heures de passages</DialogTitle>
                <DialogContent>

                    <DialogContentText>
                        Entrée l'heure de départ de l'évènement
                    </DialogContentText>
                    <br/>
                    <LocalizationProvider dateAdapter={AdapterDateFns}>
                        <TimePicker
                            label={"Heure de départ"}
                            value={this.state.startHour}
                            onChange={(newValue) => {
                                if (!newValue) return

                                this.handleChangeStartHour(newValue);
                            }}
                        />
                    </LocalizationProvider>
                    
                    <br/><br/>

                <DialogContentText>
                    Entrée l'intervalle entre les heures de passages
                </DialogContentText>    
        
                <br/>
                <Box sx={{width: "90%"}}>
                    <Slider
                        aria-label='Intervalle'
                        defaultValue={30}
                        valueLabelDisplay='auto'
                        step={5}
                        marks={marks}
                        min={5}
                        max={60}
                        onChange={this.handleIntervalChange}
                    />
                </Box>
                
                        
                <br/>
                <DialogContentText>
                    Entrée manuellement les heures des passages.
                </DialogContentText>
                <br/>
                <LocalizationProvider dateAdapter={AdapterDateFns}>
                    {this.props.hours.map((element, index) => {
                    return (
                        <React.Fragment key={index}>
                        <TimePicker
                            label={"Heure de passage #" + (index + 1)}
                            value={element.time}
                            onChange={(newValue) => {
                                if (!newValue) return
                            
                                this.setState({hoursValids: this.validateTime(newValue)});

                                this.props.onChangeHour(newValue, index);
                            }}
                        />
                        <br /><br />
                        </React.Fragment>
                    )
                    })
                    }

                {/*Bouton pour ajouter un time-slot  */}
                <Button onClick={() => this.props.onAddTimeSlot(this.state.startHour, this.state.interval)}>Ajouter une plage horaire</Button>
                {/*Bouton pour supprimer un time-slot  */}
                <Button onClick={this.props.onDeleteTimeSlot}>Supprimer une plage horaire</Button>
                
                </LocalizationProvider>
                </DialogContent>

                <DialogActions>
                    <Button onClick={this.props.handleClose}>Annuler</Button>
                    <Button 
                        disabled={!this.state.hoursValids}
                        onClick={() => {
                            this.props.handleClose()
                            this.handleEnregistrer()
                        }}
                    >
                        Enregistrer
                    </Button>
                </DialogActions>
          </Dialog>   
        )
    }
}