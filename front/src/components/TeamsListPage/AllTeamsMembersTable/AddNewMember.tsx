import React, { useState, useEffect } from 'react';
import { Dialog, DialogActions, DialogContent, DialogTitle, MenuItem, Button } from '@mui/material';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';
import { TEXTS } from '../../../lang/fr';

/**
 * Props pour le composant AddNewMember.
 * @property {array} equipes - Liste des équipes disponibles
 * @property {function} onMemberCreated - Callback pour informer le parent qu'un membre a été créé
 * @property {function} onClose - Callback pour fermer le Dialog
 * @property {boolean} open - Indique si le Dialog est ouvert ou non
 */
interface AddNewMemberProps {
    equipes: { id: number; name: string }[]; // Liste des équipes dispos
    onMemberCreated: (newMember: any) => void; // Callback pour informer le parent qu'un membre a été créé
    onClose: () => void;
    open: boolean;
}

// Constantes pour les longueurs maximales des champs
const MAX_LENGTH_FIRST_NAME = 50;
const MAX_LENGTH_LAST_NAME = 50;
const MAX_LENGTH_NUMERO_DA = 10;

/**
 * Formulaire pour ajouter un nouveau membre à une équipe existante.
 * @param equipes Liste des équipes disponibles
 * @param onMemberCreated Callback pour informer le parent qu'un membre a été créé 
 * @param onClose Callback pour fermer le Dialog
 * @param open Indique si le Dialog est ouvert ou non
 * @returns {JSX.Element} Le composant React de ce formulaire
 * 
 * @author Carlos Cordeiro
 */
const AddNewMember: React.FC<AddNewMemberProps> = ({ equipes, onMemberCreated, onClose, open }) => {
    const [newMember, setNewMember] = useState({
        first_name: '',
        last_name: '',
        numero_da: '',
        picture_consent: 0,
        team_id: '',
    });

    // useEffect pour ajout des règles de validation
    useEffect(() => {
        // Ajout des règles de validation
        ValidatorForm.addValidationRule('maxLengthFirstName', (value) => value.length <= MAX_LENGTH_FIRST_NAME);
        ValidatorForm.addValidationRule('maxLengthLastName', (value) => value.length <= MAX_LENGTH_LAST_NAME);
        ValidatorForm.addValidationRule('maxLengthNumeroDA', (value) => value.length <= MAX_LENGTH_NUMERO_DA);

        return () => {
            // Retrait des règles de validation lors du démontage du composant
            ValidatorForm.removeValidationRule('maxLengthFirstName');
            ValidatorForm.removeValidationRule('maxLengthLastName');
            ValidatorForm.removeValidationRule('maxLengthNumeroDA');
        };
    }, []);

    // Fonction pour gérer les changements dans les champs du formulaire
    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target; // Récupération du nom et de la valeur du champ
        setNewMember((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    // Fonction pour gérer la soumission du formulaire
    const handleSubmit = () => {
        onMemberCreated(newMember); // Appeler le callback pour informer le parent
        onClose(); // Fermer le Dialog
    };

    // Retourne le formulaire
    return (
        <Dialog open={open} onClose={onClose}>
            <DialogTitle>Créer un membre</DialogTitle>
            <DialogContent>
                <ValidatorForm onSubmit={handleSubmit}>
                    {/* Prénom */}
                    <TextValidator
                        margin="dense"
                        label={TEXTS.signup.firstName.label}
                        name="first_name"
                        fullWidth
                        value={newMember.first_name}
                        onChange={handleChange}
                        validators={['required', 'maxLengthFirstName']}
                        errorMessages={[
                            TEXTS.signup.firstName.error.required,
                            TEXTS.signup.firstName.error.maximum,
                        ]}
                    />
                    {/* Nom de famille */}
                    <TextValidator
                        margin="dense"
                        label={TEXTS.signup.lastName.label}
                        name="last_name"
                        fullWidth
                        value={newMember.last_name}
                        onChange={handleChange}
                        validators={['required', 'maxLengthLastName']}
                        errorMessages={[
                            TEXTS.signup.lastName.error.required,
                            TEXTS.signup.lastName.error.maximum,
                        ]}
                    />
                    {/* Numéro DA */}
                    <TextValidator
                        margin="dense"
                        label={TEXTS.signup.numeroDa.label}
                        name="numero_da"
                        fullWidth
                        value={newMember.numero_da}
                        onChange={handleChange}
                        validators={['required', 'maxLengthNumeroDA']}
                        errorMessages={[
                            TEXTS.signup.numeroDa.error.required,
                            TEXTS.signup.numeroDa.error.maximum,
                        ]}
                    />
                    {/* Consentement photo */}
                    <TextValidator
                        margin="dense"
                        label={TEXTS.signup.pictureConsent.label}
                        name="picture_consent"
                        select
                        fullWidth
                        value={newMember.picture_consent}
                        onChange={handleChange}
                        validators={['required']}
                        errorMessages={['Le consentement photo est requis']}
                    >
                        <MenuItem value={1}>{TEXTS.signup.pictureConsent.yes}</MenuItem>
                        <MenuItem value={0}>{TEXTS.signup.pictureConsent.no}</MenuItem>
                    </TextValidator>
                    {/* Équipe */}
                    <TextValidator
                        margin="dense"
                        label="Équipe"
                        name="team_id"
                        select
                        fullWidth
                        value={newMember.team_id}
                        onChange={handleChange}
                        validators={['required']}
                        errorMessages={['L\'équipe est requise']}
                    >
                        {equipes.map((team) => (
                            <MenuItem key={team.id} value={team.id}>
                                {team.name}
                            </MenuItem>
                        ))}
                    </TextValidator>
                    {/* Boutons d'action : fermer et soumettre */}
                    <DialogActions>
                        <Button onClick={onClose} color="secondary">
                            Annuler
                        </Button>
                        <Button type="submit" color="primary">
                            Créer
                        </Button>
                    </DialogActions>
                </ValidatorForm>
            </DialogContent>
        </Dialog>
    );
};

export default AddNewMember;