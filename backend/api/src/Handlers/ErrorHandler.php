<?php

namespace App\Handlers;

use App\Enums\EnumHttpCode;
use DI\DependencyException;
use DI\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Throwable;

/**
 * Classe permettant de prendre en charge les erreurs.
 * Extrait du projet Fab Lab. Modifié pour les besoins du projet.
 * @author Christopher Boisvert
 */
class ErrorHandler
{
	/**
	 * @var ResponseFactory Fabrique permettant de construire des réponses.
	 */
	protected $responseFactory;

	/**
	 * @var LogHandler Fabrique permettant de construire des réponses.
	 */
	protected $logHandler;

	/**
	 * ErrorHandler constructor.
	 * @param ResponseFactory $responseFactory Fabrique permettant de construire des réponses.
	 */
	public function __construct(ResponseFactory $responseFactory, LogHandler $logHandler)
	{
		$this->responseFactory = $responseFactory;
		$this->logHandler = $logHandler;
	}

	/**
     * Fonction qui est invoqué lorsque Slim détecte une erreur PHP.
	 * @param Request $request Requête ayant eu une erreur.
	 * @param Throwable $exception Exception soulevé.
	 * @param bool $displayErrorDetails Paramètre concernant si les erreurs doivent être affichés.
	 * @param bool $logErrors Paramètre si les erreurs sont enregistrés.
	 * @param bool $logErrorDetails Paramètre si les détails des erreurs sont enregistrés.
	 * @return ResponseInterface Retourne une réponse à l'utilisateur.
	 * @throws DependencyException Peut lancer DependencyException si le conteneur n'existe pas.
	 * @throws NotFoundException Peut lancer DependencyException si les injections de dépendence n'ont pas été trouvé.
	 */
	public function __invoke (
	    Request $request,
	Throwable $exception,
	bool $displayErrorDetails,
	bool $logErrors,
	bool $logErrorDetails
    ): ResponseInterface {

		$context = array(
			"http_error_code" => $exception->getCode() != 0 ? $exception->getCode() : EnumHttpCode::SERVER_ERROR 
		);

		$message = $exception->getMessage() . "\n" . $exception->getTraceAsString();

	    if($exception->getCode() == EnumHttpCode::NOT_FOUND)
        {
	        $this->logHandler->info($message, $context);
        }
		else if($exception->getCode() == EnumHttpCode::FORBIDDEN)
		{
			$this->logHandler->warning($message, $context);
		}
	    else
        {
	        $this->logHandler->critical($message, $context);
        }

		$response = $this->responseFactory->createResponse($context["http_error_code"]);
		return $response;
    }
}