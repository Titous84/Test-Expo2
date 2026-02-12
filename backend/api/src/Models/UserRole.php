<?php

namespace App\Models;

/**
 * Classe représentant un rôle associé à un utilisateur.
 * @author Christopher Boisvert
 * @package App\Models
 */
class UserRole
{
	public $email;
	public $roleName;

	/**
	 * UserRole constructeur.
	 * @param array $userRoleJSON Tableau des informations du rôle associé à un utilisateur.
	 */
	public function __construct( array $userRoleJSON )
	{
		$this->email = $userRoleJSON["email"];
		$this->roleName = $userRoleJSON["role"];
	}
}