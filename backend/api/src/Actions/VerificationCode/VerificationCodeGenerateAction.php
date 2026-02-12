<?php

namespace App\Actions\VerificationCode;

use App\Services\VerificationCodeService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;
use App\Models\Result;
use App\Enums\EnumHttpCode;

/**
 * inspiré de AddUserAction
 * Classe permettant de généré un code de validation
 * @author Maxime Demers Boucher
 * @package App\Actions\VerificationCode
 */
class VerificationCodeGenerateAction
{
	private $verificationCodeService;

	public function __construct(VerificationCodeService $verificationCodeService)
	{
		$this->verificationCodeService = $verificationCodeService;
	}

    /**
	 * @param Request $request Objet de requête PSR-7.
	 * @param Response $response Objet de réponse PSR-7.
	 * @param array $args Arguments passés dans la requête.
	 * @return ResponseInterface Réponse retournée par la route.
	 */
	public function __invoke(Request $request, Response $response, array $args): ResponseInterface
	{
		$body = $request->getParsedBody();
		$email = $body['email'];
		if($email === null){
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, "L' email est obligatoire.");
		}
		$resultGenerateCode = $this->verificationCodeService->generate_code($email);
		$response->getBody()->write($resultGenerateCode->to_json());
		return $response->withStatus($resultGenerateCode->get_http_code());
	}
}