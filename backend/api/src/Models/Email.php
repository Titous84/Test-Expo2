<?php

namespace App\Models;

use App\Enums\EnumHttpCode;

/**
 * Classe représentant un courriel.
 * @package App\Models
 */
class Email
{
	public $receiver;
	public $receiver_name;
	public $receiver_last_name;
    public $subject;
    public $text_content;
    public $html_content;

	/**
	 * Email constructeur.
	 * @param array $emailJSON Tableau des informations du courriel.
	 * {
	 * 	receiver:"",
	 * 	receiver_name:"",
	 * 	receiver_last_name:"",
	 * 	subject:"",
	 * 	text_content:"",
	 * 	html_content:""
	 * }
	 */
	public function __construct( array $emailJSON )
	{
		$this->receiver = $emailJSON["receiver"];
		$this->receiver_name = $emailJSON["receiver_name"];
		$this->receiver_last_name = $emailJSON["receiver_last_name"];
		$this->subject = $emailJSON["subject"];
        $this->text_content = $emailJSON["text_content"];
        $this->html_content = $emailJSON["html_content"] ?? null;
	}

	/**
	 * Fonction qui permet de valider un courriel.
	 * @param $emailJSON array Tableau représentant le courriel.
	 * @return Result Returne le résultat de la vérification à travers un objet de type Result.
	 */
	public static function validate(array $emailJSON ): Result
	{
		$messages = [];

		if(!isset($emailJSON["receiver"]))
		{
			$messages[] = "Le destinaire n'est pas présent";
		}
		if(strlen($emailJSON["receiver"]) === 0)
		{
			$messages[] = "Le destinaire ne peut pas être vide.";
		}
		if(!isset($emailJSON["receiver_name"]))
		{
			$messages[] = "Le prénom du destinataire n'est pas présent.";
		}
		if(strlen($emailJSON["receiver_name"]) === 0)
		{
			$messages[] = "Le prénom du destinaire ne peut pas être vide.";
		}
		if(!isset($emailJSON["receiver_last_name"]))
		{
			$messages[] = "Le prénom du destinataire n'est pas présent.";
		}
		if(strlen($emailJSON["receiver_last_name"]) === 0)
		{
			$messages[] = "Le prénom du destinaire ne peut pas être vide.";
		}
		if(!isset($emailJSON["subject"]))
		{
			$messages[] = "Le sujet du courriel n'est pas présent.";
		}
		if(strlen($emailJSON["subject"]) === 0)
		{
			$messages[] = "Le sujet ne peut pas être vide.";
		}
		if(!isset($emailJSON["text_content"]))
		{
			$messages[] = "Le contenu texte du courriel n'est pas présent.";
		}
		if(strlen($emailJSON["text_content"]) === 0)
		{
			$messages[] = "Le contenu texte ne peut pas être vide.";
		}
		if(isset($emailJSON["text_content_html"]) && strlen($emailJSON["text_content_html"]) === 0)
		{
			$messages[] = "Le contenu html ne peut pas être vide s'il est présent.";
		}

		return new Result(EnumHttpCode::BAD_REQUEST, $messages);
	}

	/**
	 * Fonction permettant d'obtenir le nom complet de la personne envoyant un courriel.
	 * @return string Retourne le nom complet de la personne.
	 */
	public function get_full_name(): string
	{
		return $this->receiver_name . " " . $this->receiver_last_name;
	}

	/**
	 * Fonction qui permet de savoir si le courriel est html ou pas.
	 * @return bool Retourne vrai s'il est html et faux dans le cas contraire.
	 */
	public function is_html(): bool
	{
		if (strlen($this->html_content) != 0) return true;
		return false;
	}
}