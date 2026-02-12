/**
 * Mathieu Sévégny et Tristan Lafontaine
 */
export const MAX_LENGTH_EMAIL = 255;
export const MAX_LENGTH_FIRST_NAME = 50;
export const MAX_LENGTH_LAST_NAME = 50;
export const MAX_LENGTH_TITLE_STAND = 30;
export const MAX_LENGTH_DESCRIPTION_STAND = 250;
export const MAX_LENGTH_CONTACT_PERSON_NAME = 50;
export const MAX_LENGTH_PWD = 255;
export const EMPTY_STRING =  "";
export const REGEX = {
    CONTACT_PERSON:"^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(cegepvicto.ca)$",
    EMAIL:/^[^\s@]+@[^\s@]+\.[^\s@]+$/
}