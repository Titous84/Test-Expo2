<?php

namespace App\Validators;

use App\Enums\EnumRegex;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Validator
 */
class Validator
{
    /**
     * Vérification de l'adresse courriel
     * @param string $email
     * @author Tristan Lafontaine
     * @return boolean
     */
    public function verify_email(string $email): bool
    {
        return !(bool)preg_match(EnumRegex::EMAIL_REGEX, $email);
    }

    /**
     * Vérifie qu'un champs existe et n'est pas vide.
     * @param array $array
     * @param string $field
     * @author William Boudreault
     * @return boolean
     */
	public function verify_field_exists($array, $field): bool
	{
		return array_key_exists($field, $array) && !$this->is_empty($array[$field]);
	}
           
    /**
     * verifyEmailContactRessource
     * Vérification des adresses pour les personnes-ressources
     * @param  mixed $email
     * @author Tristan Lafontaine
     * @return bool
     */
    public function verify_email_contact_person(string $email): bool
    {
        return !(bool)preg_match(EnumRegex::EMAIL_REGEX_PERSON_CONTACT, $email);
    }

    /**
     * isEmpty
     * Vérifier si la valeur n'est pas null
     * @param  mixed $value
     * @author Christopher Boisvert
     * @return bool
     */
    public function is_empty(string $value): bool
    {
        return !strlen($value);
    }
    
    /**
     * isSmallerThan
     * Vérifier si la valeur est plus petit que le maxLength
     * @param  mixed $value
     * @param  mixed $maxLength
     * @author Christopher Boisvert
     * @return void
     */
    public function is_smaller_than(string $value, int $maxLength)
    {
        if(strlen($value) >= $maxLength) return true;
        return false;
    }

    /**
     * has_length_of
     * Fonction permettant de savoir si une chaîne de caractère à une certaine longueur.
     * @author Christopher Boisvert
     * @return bool Retourne vrai si la longueur voulu est égale à la valeur fourni, et faux dans le cas contraire.
     */
    public function has_length_of(string $value, int $length): bool
    {
        return strlen($value) == $length;
    }
    
    /**
     * sanitize
     * @param  mixed $value
     * @author Christopher Boisvert
     * @return void
     */
    public function sanitize(string $value)
    {
        return htmlspecialchars($value);
    }
}