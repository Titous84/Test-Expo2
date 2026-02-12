<?php

namespace App\Models;

/**
 * Classe User.
 * @author Christopher Boisvert
 * @package App\Models
 */
class User
{
	/**
	 * @var int|null ID de l'utilisateur.
	 */
	public $id;

	/**
	 * @var string Prénom de l'utilisateur.
	 */
	public $first_name;

	/**
	 * @var string Nom de l'utilisateur.
	 */
	public $last_name;

	/**
	 * @var string Nom utilisateur de l'utilisateur.
	 */
	public $username;

	/**
	 * @var string|null Mot de passe de l'utilisateur.
	 */
	public $pwd;

	/**
	 * @var string Email de l'utilisateur.
	 */
	public $email;

	/**
	 * @var string Numéro de DA de l'utilisateur.
	 */
	public $numero_da;

	/**
	 * @var string URL vers l'image de l'utilisateur.
	 */
	public $picture;

	/**
	 * @var string Consentement de l'utilisateur à afficher sa photo.
	 */
	public $picture_consent;

	/**
	 * @var string Token qui permet de changer son mot de passe.
	 */
	public $reset_token;

	/**
	 * @var string Token qui permet d'activer l'utilisateur.
	 */
	public $activation_token;

	/**
	 * @var int|null Statut d'activation de l'utilisateur.
	 */
	public $activated;

	/**
	 * @var int|null Statut de bannissement de l'utilisateur.
	 */
	public $blacklisted;

	/**
	 * @var int|null Rôle de l'utilisateur.
	 */
	public $role_id;

	/**
	 * Users constructeur.
	 * @param $userJSON
	 */
    public function __construct($userJSON)
    {
        $this->id = $userJSON["id"] ?? null;
        $this->first_name = $userJSON["first_name"];
        $this->last_name = $userJSON["last_name"];
		$this->username = $userJSON["username"];
        $this->pwd = password_hash($userJSON["pwd"], PASSWORD_DEFAULT);
	    $this->email = $userJSON["email"];
		$this->numero_da = $userJSON["numero_da"] ?? null;
		$this->picture = $userJSON["picture"] ?? null;
		$this->picture_consent = $userJSON["picture_consent"];
		$this->reset_token = $userJSON["reset_token"] ?? null;
		$this->activation_token = $userJSON["activation_token"] ?? null;
		$this->activated = $userJSON["activated"];
		$this->blacklisted = $userJSON["blacklisted"];
        $this->role_id = $userJSON["role_id"];
    }
}