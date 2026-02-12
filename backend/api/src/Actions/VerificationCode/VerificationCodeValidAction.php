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
 * Classe permettant de validé un code de vérification
 * @author Maxime Demers Boucher
 * @package App\Actions\VerificationCode
 */
class VerificationCodeValidAction
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
		$code = $request->getAttribute('code');
		if($code === null){
			return $response->withStatus(EnumHttpCode::BAD_REQUEST, "Le code est obligatoire.");
		}
		$resultValidation = $this->verificationCodeService->verify_code($code);
		$response->getBody()->write($resultValidation ->to_json());
		return $response->withStatus($resultValidation ->get_http_code());
	}
}