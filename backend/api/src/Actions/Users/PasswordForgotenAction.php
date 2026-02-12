<?php

namespace App\Actions\Users;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Models\Result;
use App\Enums\EnumHttpCode;
use App\Services\TokenService;

/**
 * Classe permettant d'ajouter un user.
 * @author Maxime Demers Boucher
 * @package App\Actions\Users
 */
class PasswordForgotenAction
{
    /**
     * Service du user
     */
    private $UserService;

    /**
     * Constructeur de la classe
     */
    public function __construct(UserService $UserService)
	{
		$this->UserService = $UserService;
	}

    /**
     * inspiré de get ActiveUserAction
	 * Fonction invoquée lors de l'appelle de la classe GetActiveUsers.
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$body = $request->getParsedBody();
		$email = $body['email'];
		$code = $body['verificationCode'];
		if (empty($email)) {
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, 'Le email est obligatoire.');
		}
		$resultMotDePasseOublier = $this->UserService->send_email_PWF($email,$code);
		$response->getBody()->write($resultMotDePasseOublier->to_json());
		return $response->withStatus($resultMotDePasseOublier->get_http_code());
	}
}