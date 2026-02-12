import Link from "./link";

/**
 * Interface représentant un role contenant des liens accessibles par l'utilisateur
 */
export default interface Role {
    name : string,
    links : Link[]
}