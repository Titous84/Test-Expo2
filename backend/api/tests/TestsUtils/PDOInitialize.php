<?php

namespace Test\TestsUtils;
use PDO;
use Test\TestsUtils\TestingLogger;

class PDOInitialize{
    /**
	 * PDO
	 * Permet d'initialiser la connexion à la base de données
	 * @return PDO
	 */
	function PDO(): PDO
	{
		TestingLogger::log("Initialisation BD");
		$host = $_ENV["dbhost"];
		$dbname = $_ENV["dbname"];
		$username = $_ENV["dbusername"];
		$password = $_ENV["dbpassword"];
		$charset = 'utf8';
		$collate = 'utf8_unicode_ci';
		$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_PERSISTENT => false,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
		];
		TestingLogger::log("Création du PDO");		
		return new PDO($dsn, $username, $password, $options);
	}
}