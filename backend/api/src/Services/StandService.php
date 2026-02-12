<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Repositories\StandRepository;
use App\Handlers\LogHandler;
use Exception;

/**
 * Service pour les stands
 * @author Alex Des Ruisseaux
 */
class StandService
{
	/**
	 * @var StandRepository Dépôt lié à la bd permettant d'accéder aux stands.
	 */
	private $standRepository;

	/**
	 * @var LogHandler Gestionnaire des erreurs.
	 */
	private $logHandler;

	/**
	 * Stand constructeur.
	 * @param StandRepository $standRepository Dépôt des stands.
	 */
	public function __construct( StandRepository $standRepository, LogHandler $logHandler )
    {
        $this->standRepository = $standRepository;
		$this->logHandler = $logHandler;
    }

	/**
	 * Fonction qui permet d'ajouter un stand.
	 * @param array $body Tableau avec les donnees a changer.
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
    public function add_time_stand($body): Result
    {
	    try
	    {
			$timeStand = $body["hour"];//heure de l'evaluation
			$judgeId = $body["judgeId"];//id du juge
			$teamStand = $body["standId"];//numero de stand
			$surveyId = $body["surveyId"];//id du survey

			if(!$timeStand||!$judgeId||!$teamStand||!$surveyId){
				return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de l'ajout du temps d'evaluation!"), null);
			}
			//On essaie d'ajouter le temps du stand dans la bd.
		    $resultAddStand = $this->standRepository->add_time_stand($timeStand,$teamStand,$judgeId,$surveyId);

			//Si une ligne est négative est zéro, une erreur est survenue.
		    if( $resultAddStand == 0 )
			{
			    return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de la modification du stand."),FALSE);
		    }

			//On retourne une résultat de succès de l'ajout du stand.
			return new Result(EnumHttpCode::SUCCESS, array("Le stand a été modifié !"),TRUE);
	    }
		catch ( Exception $e )
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
    }
	/**
	 * Fonction qui permet de verifier si il y a un conflit entre le stand et le juge
	 * @param array $body Tableau avec les donnees
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.(true si bon false si pas bon)
	 */
    public function conflict_stand($body): Result
    {
	    try
	    {
			$judgeName = $body["judge_name"];//nom complet du juge
			$teamStand = $body["stand"];//numero de stand

			if(!$judgeName||!$teamStand){
				return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de l'ajout du temps d'evaluation!"), null);
			}
			//On essaie d'ajouter le temps du stand dans la bd.
		    $resultAddStand = $this->standRepository->conflict_stand($teamStand,$judgeName);

		    if( $resultAddStand > 0 )
			{
				return new Result(EnumHttpCode::SUCCESS, array("Il y a des conflits entre le juge et le stand"),false);
		    }else{
				//retourner un succes si il n'y a pas de conflits entre le juge et le stand
				return new Result(EnumHttpCode::SUCCESS, array("Aucun conflits entre le juge et le stand"),true);
			}			
	    }
		catch ( Exception $e )
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
    }
}