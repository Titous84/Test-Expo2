<?php

namespace App\Models;

/**
 * Classe représentant un résultat d'une action.
 */
class Result
{
	/**
	 * @var int Code http de l'action.
	 */
	private $http_code;

	/**
	 * @var array Tableau de message à retourner.
	 */
	private $message;

	/**
	 * @var mixed|null Contenu ou données à retourner s'il y lieu.
	 */
	private $content;

	/**
	 * Result constructeur.
	 * @param int $http_code Code http de l'action.
	 * @param array $message Tableau de message à retourner.
	 * @param mixed $content Contenu ou données à retourner s'il y lieu.
	 */
	public function __construct(int $http_code, array $message, $content = null)
	{
		$this->http_code = $http_code;
		$this->message = $message;
		$this->content = $content;
	}

	/**
	 * Fonction qui permet d'obtenir le code http.
	 * @return int Code HTTP.
	 */
	public function get_http_code(): int
	{
		return $this->http_code;
	}

	/**
	 * Fonction qui permet d'obtenir les messages.
	 * @return array Tableau de message.
	 */
	public function get_message(): array
	{
		return $this->message;
	}

	/**
	 * Fonction qui permet d'obtenir le contenu.
	 * @return mixed Texte ou objet.
	 */
	public function get_content()
	{
		return $this->content;
	}

	/**
	 * Fonction qui transforme le présent objet en JSON.
	 * @return string JSON retourné.
	 */
	public function to_json():string
	{
		return json_encode(array( "message" => $this->get_message(), "content" => $this->get_content()),JSON_UNESCAPED_SLASHES);
	}
}