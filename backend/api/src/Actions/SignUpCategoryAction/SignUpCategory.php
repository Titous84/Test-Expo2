<?php
namespace App\Actions\SignUpCategoryAction;

use App\Services\SignUpCategoryService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * SignUpCategory
 * @author Tristan Lafontaine
 */
class SignUpCategory{
    
    /**
     * signUpCategoryService
     *
     * @var SignUpCategoryService Permet d'avoir assez à la classe SignUpCategoryService'
     */
    private $signUpCategoryService;

    public function __construct(SignUpCategoryService $signUpCategoryService)
    {
		$this->signUpCategoryService = $signUpCategoryService;
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
		$resultCategory = $this->signUpCategoryService->get_all_category();
        $response->getBody()->write($resultCategory->to_json());
		return $response->withStatus($resultCategory->get_http_code());
	}
}