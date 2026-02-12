import * as React from 'react';
import Box from '@mui/material/Box';
import Stepper from '@mui/material/Stepper';
import Step from '@mui/material/Step';
import StepButton from '@mui/material/StepButton';
import { TEXTS } from '../../lang/fr';
import ButtonExposat from '../button/button-exposat';
import styles from "./horizontal-non-linear-stepper.module.css";


interface HorizontalNonLinearStepperProps {
    steps:Array<String>,
    setActualSection:(actuelSectionId:number) => void,
    returnToMenu: () => void
}

export default function HorizontalNonLinearStepper(props:HorizontalNonLinearStepperProps) {
    const [activeStep, setActiveStep] = React.useState(0);
  
    const totalSteps = () => {
      return props.steps.length;
    };
  
    const isLastStep = () => {
      return activeStep === totalSteps() - 1;
    };
  
    const handleNext = () => {
      const newActiveStep = !isLastStep() ? activeStep + 1 : activeStep
      setActiveStep(newActiveStep);
      props.setActualSection(newActiveStep)
    };
  
    const handleBack = () => {
      const newActiveStep = activeStep > 0 ? activeStep - 1 : activeStep
      setActiveStep(newActiveStep);
      props.setActualSection(newActiveStep)
    };
  
    const handleStep = (step: number) => () => {
      setActiveStep(step);
      props.setActualSection(step)
    };
  
    return (
      <Box sx={{ width: '100%' }}>
        <Stepper nonLinear activeStep={activeStep}>
          {props.steps.map((label, index) => (
            <Step key={"step" + index}>
              <StepButton color="inherit" onClick={handleStep(index)}>
                <Box sx={{ display:"hidden" }}>{label}</Box>
              </StepButton>
            </Step>
          ))}
        </Stepper>
        <div>
        <React.Fragment>
          <Box sx={{ display: 'flex', flexDirection: 'row', pt: 2 }}>
            <ButtonExposat
              className={styles.buttonSection}
              disabled={!(activeStep > 0)}
              onClick={handleBack}
            >
              {TEXTS.survey.textButtonPreviousSection}
            </ButtonExposat>                
            <Box sx={{ flex: '1 1 auto' }} />
            <ButtonExposat className={styles.buttonSection} onClick={props.returnToMenu}>{TEXTS.survey.textReturnToPrincipalMenu}</ButtonExposat>
            <Box sx={{ flex: '1 1 auto' }} />
            <ButtonExposat className={styles.buttonSection} disabled={!(activeStep < props.steps.length - 1)} onClick={handleNext}>
              {TEXTS.survey.textButtonNextSection}
            </ButtonExposat>
          </Box>
        </React.Fragment>
        </div>
      </Box>
    );
  }