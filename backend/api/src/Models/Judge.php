<?php

namespace App\Models;

/**
 * Classe Juge.
 * @author Jean-Philippe Bourassa
 * @package App\Models
 */
class Judge
{
	/**
	 * @var int|null ID du juge.
	 */
	public $id;

	
	/**
	 * @var int|null User ID du juge.
	 */
	public $user_id;

	/**
	 * @var string Prénom du juge.
	 */
	public $firstName;

	/**
	 * @var string Nom du juge.
	 */
	public $lastName;

	/**
	 * @var string|null Mot de passe du juge.
	 */
	public $pwd;

	/**
	 * @var string Email du juge.
	 */
	public $email;

	/**
	 * @var string URL vers l'image du juge.
	 */
	public $picture;

	/**
	 * @var boolean Consentement du juge à afficher sa photo.
	 */
	public $pictureConsent;

	/**
	 * @var int|null Statut d'activation du juge.
	 */
	public $activated;

	/**
	 * @var int|null Statut de bannissement du juge.
	 */
	public $blacklisted;

	/**
	 * @var int|null Rôle du juge.
	 */
	public $role_id;

	/**
	 * @var string|null Catégorie associé au juge.
	 */
	public $category;

	/**
	 * Judge constructeur.
	 * @param $judgeJSON
	 */
	public function __construct($judgeJSON)
	{
		$this->id = $judgeJSON["id"] ?? null;
		$this->user_id = $judgeJSON["user_id"] ?? null;
		$this->firstName = $judgeJSON["firstName"];
		$this->lastName = $judgeJSON["lastName"];
		$this->pwd = password_hash($judgeJSON["pwd"], PASSWORD_DEFAULT);
		$this->email = $judgeJSON["email"];
		$this->picture = $judgeJSON["picture"] ?? null;
		$this->pictureConsent = $judgeJSON["pictureConsent"];
		$this->activated = $judgeJSON["activated"] ?? false;
		$this->blacklisted = $judgeJSON["blacklisted"] ?? false;
		$this->role_id = $judgeJSON["role_id"] ?? null;
		$this->category = $judgeJSON["category"] ?? null;
	}
}
