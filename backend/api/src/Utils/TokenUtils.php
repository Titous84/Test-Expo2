<?php

namespace App\Utils;

use App\Services\TokenService;
use Slim\Psr7\Request;
use App\Enums\EnumHttpCode;
use App\Models\Result;

/**
 * Classe permettant d'utiliser des tokens.
 */
abstract class TokenUtils{
    /**
     * Cherche le token dans la requête HTTP.
     * @author Mathieu Sévégny
     * @param $request API request
     * @return string|null
     */
    public static function get_token_from_request(Request $request){
        $token = $request->getHeaderLine("Authorization");

        if ($token == ""){
            return null;
        }

        $tableau_token = explode(" ", $token);
        return $tableau_token[1];
    }

    /**
     * Cherche l'identifiant du role contenu dans le token.
     * @author Mathieu Sévégny
     * @param $tokenService TokenService
     * @param $token
     * @return int|null
     */
    public static function get_role_id_from_token(TokenService $tokenService,string $token){
        try{
            return $tokenService->get_claim($token,'role_id');
        }
        catch (\Exception $e){
            return null;
        }
    }
    
    /**
     * Vérifie si l'usager est dans les rôles autorisés.
     * @author Mathieu Sévégny
     * @param $request Requête
     * @param $tokenService TokenService
     * @param $permitted_roles Rôles autorisés
     * @return Result|null Retourne null si autorisé, sinon un objet Result
     */
    public static function is_user_in_permitted_roles(Request $request,TokenService $tokenService,array $permitted_roles){
        $token = TokenUtils::get_token_from_request($request);

        $result = new Result(EnumHttpCode::FORBIDDEN, ["Vous n'avez pas les droits d'accès suffisants!"], null);

        if ($token === null) {
            return $result;
        }
        
		$role_id = TokenUtils::get_role_id_from_token($tokenService,$token);
        if ($role_id === null){
            return $result;
        }

		$role_name = $tokenService->get_role_name($role_id);

        for ($i=0; $i < count($permitted_roles); $i++) { 
            if ($role_name === $permitted_roles[$i]) {
                return null;
            }
        }

        return $result;
    }
}