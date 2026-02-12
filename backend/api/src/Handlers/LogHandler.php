<?php

namespace App\Handlers;

use Psr\Log\LoggerInterface;

/**
 * Classe permettant de prendre en charge les erreurs.
 */
class LogHandler implements LoggerInterface
{
	/**
	 * Fonction qui permet de créer un log de type EMERGENCY.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function emergency( $message, array $context = array() )
    {
		$this->log("EMERGENCY", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type ALERT.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function alert( $message, array $context = array() ) {
		$this->log("ALERT", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type CRITICAL.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function critical( $message, array $context = array() ) {
		$this->log("CRITICAL", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type ERROR.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function error( $message, array $context = array() ) {
		$this->log("ERROR", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type WARNING.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function warning( $message, array $context = array() ) {
		$this->log("WARNING", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type NOTICE.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function notice( $message, array $context = array() ) {
		$this->log("NOTICE", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type INFO.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function info( $message, array $context = array() ) {
		$this->log("INFO", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log de type DEBUG.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function debug( $message, array $context = array() ) {
		$this->log("DEBUG", $message, $context);
	}

	/**
	 * Fonction qui permet de créer un log.
	 * @param $level string Niveau du log.
	 * @param $message string Message à écrire.
	 * @param array $context Tableau représentant le contexte de l'erreur.
	 * @return void
	 */
	public function log( $level, $message, array $context = array() ) {

		$datetime_log = date('Y-m-d H:i:s');
		$date_nom_log = date("Y-m-d");
		$chemin_fichier_log = "../log/error-" . $date_nom_log . ".log";
		$http_error_code = $context["http_error_code"];

		if(!file_exists("../log"))
		{
			mkdir("../log");
		}

		$log_string = "-- $level | " . $http_error_code . " | $datetime_log | " . $this->obtenir_adresse_ip() . " --\n";
		$log_string .= $message . "\n";
		$log_string .= "-- $level " . $http_error_code . " | $datetime_log | " . $this->obtenir_adresse_ip() . " --\n";

		error_log($log_string, 3, $chemin_fichier_log);
	}

	public function obtenir_adresse_ip():string
	{
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "localhost";
	}
}