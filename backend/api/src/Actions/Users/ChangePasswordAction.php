<?php

namespace App\Actions\Users;

use App\Services\ChangePasswordService;
use App\Enums\EnumHttpCode;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant de changer le mot de passe d'un usager
 * @author Samuel Lambert
 */
class ChangePasswordAction
{
    private $ChangePasswordService;

    public function __construct(ChangePasswordService $_ChangePasswordService)
    {
        $this->ChangePasswordService = $_ChangePasswordService;
    }
    /**
     * Fonction invoquée lors de l'appelle de la classe DefaultAction
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de réponse PSR-7.
     * @param array $args Arguments passés dans la requête.
     * @return ResponseInterface Réponse retournée par la route.
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $email = $body['email'];
		if (empty($email)) {
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, 'Le email est obligatoire.');
		}

		$pwd = $body['pwd'];
		if (empty($pwd)) {
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, 'Le mot de passe est obligatoire.');
		}

        $resultUpdatePwd = $this->ChangePasswordService->update_pwd($email, $pwd);
        
        $response->getBody()->write($resultUpdatePwd->to_json());
        return $response->withStatus($resultUpdatePwd->get_http_code());
    }
}
?>
