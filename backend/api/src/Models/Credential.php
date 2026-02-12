<?php

namespace App\Models;

use App\Enums\EnumHttpCode;

/**
 * Classe représentant des identifiants de connexion.
 * @package App\Models
 */
class Credential
{
	/**
	 * @var string Email de l'utilisateur.
	 */
	public $email;

	/**
	 * @var string Mot de passe de l'utilisateur.
	 */
	public $password;

	/**
	 * Credential constructeur.
	 *
	 * @param array $credentialJSON
	 */
	public function __construct(array $credentialJSON)
	{
		$this->email = $credentialJSON["email"];
		$this->password = $credentialJSON["password"];
	}

	public static function validate($credentialJSON): Result
	{
		$messages = [];

		if(!isset($credentialJSON["email"]))
		{
			$messages[] = "Le courriel n'est pas présent";
		}
		if(!isset($credentialJSON["password"]))
		{
			$messages[] = "Le mot de passe n'est pas présent";
		}

		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}
}