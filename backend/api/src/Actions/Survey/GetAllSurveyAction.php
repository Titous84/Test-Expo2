<?php


namespace App\Actions\Survey;

use App\Services\SurveyService;
use EnumHttpCode;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/**
 * Classe permettant d'aller cherhcer les types de formulaires
 * @author Tomy Chouinard
 */
class GetAllEvaluationAction
{
    private $contestService;

    public function __construct(SurveyService $_contestService)
    {
        $this->contestService = $_contestService;
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
        $resultGetAllTypes = $this->contestService->get_all_evaluation();
        $response->getBody()->write($resultGetAllTypes->to_json());
        return $response->withStatus($resultGetAllTypes->get_http_code());
    }
}
?>
