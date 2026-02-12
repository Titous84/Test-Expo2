import { RoleName } from "../../router/routes";

/**
 * Role de l'usager retourné par la route d'api /role
 * @author Mathieu Sévégny
 */
export default interface UserRole{
    id:number;
    name:RoleName;
}