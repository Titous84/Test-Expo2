<?php
namespace App\Services;

use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Classe TwigService permet de générer du html avec des v{ariables.
 * @author Mathieu Sévégny
 * @package App\Services
 */
class TwigService
{
	/**
	 * @var Environment permet de générer du html avec des variables.
	 */
	public $twig;

	/**
	 * TwigService constructeur.
	 */
	public function __construct()
	{
		try
		{
			$loader = new FilesystemLoader(__DIR__ . '/../../src/Interfaces');
    		$this->twig = new Environment($loader,[]);
		}
		catch(Exception $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
		}
	}

}