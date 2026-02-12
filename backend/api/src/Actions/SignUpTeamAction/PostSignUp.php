<?php

namespace App\Actions\SignUpTeamAction;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Services\SignUpTeamService;

/**
 * Classe permettant de gérer la page par défaut.
 */
class PostSignUp
{	
	/**
	 * signUpTeamService
	 *
	 * @var SignUpTeamService Permet d'avoir assez à la classe SignUpTeamService'
	 */
	private $signUpTeamService;

    public function __construct(SignUpTeamService $signUpTeamService)
    {
		$this->signUpTeamService = $signUpTeamService;
    }
	
	/**
     * Fonction invoquée lors de l'appelle de la classe DefaultAction
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de réponse PSR-7.
     * @param array $args Arguments passés dans la requête.
     * @return ResponseInterface Réponse retournée par la route.
     */
    public function __invoke(Request $request, Response $response, $args): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'application/json');

        try {
            $resultSignUp = $this->signUpTeamService->add_signup_team($request->getParsedBody());
            $response->getBody()->write($resultSignUp->to_json());
            return $response->withStatus($resultSignUp->get_http_code());
        } catch (\Exception $e) {
            $errorResponse = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'content' => null
            ];
            $response->getBody()->write(json_encode($errorResponse));
            return $response->withStatus(500);
        }
    }
}