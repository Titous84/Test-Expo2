<?php

namespace App\Actions\Administrators;

use App\Services\UserService;
use App\Services\TokenService;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Utils\TokenUtils;

/**
 * Classe permettant de supprimer une liste d'administrateurs par une liste de leurs ids.
 * @author Antoine Ouellette
 * @package App\Actions\Administrators
 */
class DeleteAdministratorsByIdsAction
{
    /**
     * Permet d'avoir accès au service UserService
     * @var UserService
     */
    private $userService;

    /**
     * Permet d'avoir accès au TokenService
     */
    private $tokenService;

    /**
     * Constructeur de la classe DeleteAdministratorsByIdsAction
     * @author Antoine Ouellette
     * @param UserService $userService
     * @param TokenService $tokenService
     */
    public function __construct(UserService $userService, TokenService $tokenService)
    {
        $this->userService = $userService;
        $this->tokenService = $tokenService;
    }

    /**
     * Méthode appellée lors de l'appel de la classe DefaultAction.
     * @param Request $request Objet de requête PSR-7.
     * @param Response $response Objet de réponse PSR-7.
     * @param array $args Arguments passés dans la requête.
     * @return ResponseInterface Réponse retournée par la route.
     */
    public function __invoke(Request $request, Response $response, array $args): ResponseInterface
    {
        // Vérifie si l'utilisateur a le rôle d'administrateur.
        // Si l'utilisateur a le rôle d'administrateur, null est retourné.
        // Sinon, un message d'erreur est retourné.
        $isUserPermitted = TokenUtils::is_user_in_permitted_roles($request, $this->tokenService, ["Admin"]);

        // Si l'utilisateur n'a pas le rôle d'administrateur.
        if ($isUserPermitted !== null) {
            // Retourne le message d'erreur que l'utilisateur n'a pas les permissions.
            $response->getBody()->write($isUserPermitted->to_json());
            return $response->withStatus($isUserPermitted->get_http_code());
        }

        // Récupère le corps de la requête qui possède un champ ids dont la valeur est un tableau.
        $administratorsToDeleteIds = $request->getParsedBody()['ids'] ?? null;

        // Si le champs "ids" n'est pas présent ou vide, retourne une erreur 400 (erreur dans la requête du client).
        if ($administratorsToDeleteIds === null || empty($administratorsToDeleteIds)) {
            $response->getBody()->write("Le champs 'ids' est requis. Sa valeur doit être un tableau des ids des administrateurs à supprimer.");
            return $response->withStatus(400);
        }

        // Le service retourne un objet avec les informations à mettre dans le corps de la réponse.
        $serviceResponse = $this->userService->delete_administrators_by_ids($administratorsToDeleteIds);

        // Ajoute les messages du service dans le corps de la réponse.
        $response->getBody()->write($serviceResponse->to_json());
        // Envoie la réponse avec le code HTTP.
        return $response->withStatus($serviceResponse->get_http_code()); // Récupère le code HTTP dans l'objet retourné par le service.
    }
}