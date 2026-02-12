<?php

use App\Handlers\ErrorHandler;
use App\Middlewares\CorsMiddleware;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

/**
 * On charge Dotenv.
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env.prod');

/**
 * On créer le constructeur de conteneur.
 */
$containerBuilder = new ContainerBuilder();

/**
 * On construit le conteneur
 */
$container = $containerBuilder->build();

/**
 * On ajoute une option qui indique le dossier de téléchargement
 */
$container->set('upload_directory', __DIR__ . '\Uploads');

/**
 * On build l'application 
 */
AppFactory::setContainer($container);
$app = AppFactory::create();

/**
 * On ajoute les définitions des dépendences.
 */

$container->set("PDO", function(){
    $host =$_ENV["dbhost"];
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

    return new PDO($dsn, $username, $password, $options);
});

/**
 * On instancie l'application
 */
AppFactory::setContainer($container);
$app = AppFactory::create();

/**
 * Permet de retourner le traiter le body des requêtes.
 */
$app->addBodyParsingMiddleware();

/**
 * Permet les requêtes CORS
 */
if(! ( $_ENV["production"] === "true" ))
{
	$app->add(CorsMiddleware::class);
}

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Gestion des erreurs
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
ini_set("display_errors", ! ( $_ENV["production"] === "true" ) );
$errorMiddleware = $app->addErrorMiddleware(false, false, false);
$errorMiddleware->setDefaultErrorHandler( ErrorHandler::class);

/**
 * On incluent les routes de Slim V4.
 */
require_once("../config/routes.php");

/**
 * On run l'application
 */
$app->run();