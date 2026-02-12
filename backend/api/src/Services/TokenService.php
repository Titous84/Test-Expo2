<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Credential;
use App\Models\Result;
use App\Repositories\TokenRepository;

/**
 * Classe TokenService.
 * @package App\Services
 */
class TokenService
{
	/**
	 * @var TokenRepository Dépôt lié à la bdd permettant d'accéder aux tokens.
	 */
	public $tokenRepository;

	/**
	 * TokenService constructeur.
	 * @param TokenRepository $tokenRepository Dépôt des tokens.
	 */
	public function __construct(TokenRepository $tokenRepository)
	{
		$this->tokenRepository = $tokenRepository;
	}

	/**
	 * Fonction qui permet d'obtenir un token.
	 * @param $credentialJSON mixed Tableau représentant les crédentiels de l'utilisateur.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_token( $credentialJSON ): Result
	{
		$credentialResult = Credential::validate($credentialJSON);

		if(count($credentialResult->get_message()) > 0)
		{
			return $credentialResult;
		}

		$credential = new Credential($credentialJSON);
		$token = $this->tokenRepository->get_token($credential);

		if($token == null)
		{
			return new Result(EnumHttpCode::BAD_REQUEST, array("Vos identifiants ne concorde pas avec notre base de données !"));
		}

		return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouvé votre token !"), $token);
	}

	/**
	 * Fontion qui permet de vérifer un token.
	 * @param $token_string string Token à vérifier.
	 * @return bool Retourne vrai si le token est valide et faux dans le cas contraire.
	 */
	public function verify_token(string $token_string)
	{
		return $this->tokenRepository->verify_token($token_string);
	}

	/**
	 * Fonction permettant d'obtenir un claim sur un token.
	 * @param $token_string string Token.
	 * @param $name_claim string Nom du claim.
	 * @return mixed|null Peut retourner un objet ou peut retourner null.
	 */
	public function get_claim(string $token_string, string $name_claim)
	{
		return $this->tokenRepository->get_claim($token_string, $name_claim);
	}
	/**
	 * Fonction permettant d'obtenir un claim sur un token.
	 * @param $token_string string Token.
	 * @param $name_claim string Nom du claim.
	 * @return string|null Peut retourner un objet ou peut retourner null.
	 */
	public function get_role_name(int $role_id)
	{
		return $this->tokenRepository->get_role_name($role_id);
	}
}