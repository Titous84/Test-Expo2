<?php

	namespace App\Repositories;

use App\Handlers\LogHandler;
use PDO;

	/**
	 * Classe de base Repository.
	 */
    class Repository
    {
	    /**
	     * @var PDO Base de données via PDO.
	     */
        protected $db;

		/**
		 * @var LogHandler Permet de loggé des erreurs SQL.
		 */
		protected $logHandler;

	    /**
	     * Repository contructeur.
	     * @param PDO $db Base de données via PDO.
	     */
        public function __construct(PDO $db, LogHandler $logHandler)
        {
            $this->db = $db;
			$this->logHandler = $logHandler;
        }
    }