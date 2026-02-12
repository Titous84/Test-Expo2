<?php

namespace App\Actions\Resultat;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\ResultatService;
use App\Utils\TokenUtils;
use App\Services\TokenService;

/**
 * Classe permettant de gérer la page par défaut.
 * @author Tommy Garneau
 * @package App\Actions\Resultat
 * Inspiré du fichier DeleteTeamsInfos.php
 */
class DeleteResultJudge
{	
	
	/**
	 * @var ResultatService Dépôt liée à la classe ResultatService
	 */
	private $resultatService;
	
	/**
	 * @var TokenService Dépôt liée au service TokenService
	 */
	private $tokenService;
    
    /**
     * Constructeur de la classe DeleteResultJudge
     *
     * @param  ResultatService $resultatService
     * @param  TokenService $tokenService
     * @return void
     */
    public function __construct(ResultatService $resultatService, TokenService $tokenService)
    {
		$this->resultatService = $resultatService;
		$this->tokenService = $tokenService;
    }
	
	/**
	 * Fonction invoquée lors de l'appelle de la classe DefaultAction
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response): ResponseInterface
	{
		$result = TokenUtils::is_user_in_permitted_roles($request,$this->tokenService,["Admin"]);

		if ($result != null){
			$response->getBody()->write($result->to_json());
			return $response->withStatus($result->get_http_code());
		}

        // Récupère les données de la requête
        $requestData = $request->getParsedBody();

        // Appelle le service avec les arguments extraits
        $teamName = $requestData['teamName'];
        $judgeId = (int) $requestData['judgeId']; 
        $resultat = $this->resultatService->delete_judge_resultat($teamName, $judgeId);
		

        $response->getBody()->write($resultat->to_json());
		return $response->withStatus($resultat->get_http_code());
	}
}