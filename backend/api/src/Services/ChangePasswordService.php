<?php
namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Models\User;
use App\Repositories\ChangePasswordRepository;
use Exception;

/**
 * Class ChangePasswordService
 * @author Samuel Lambert
 * @package App\Services
 */
class ChangePasswordService
{
    /**
     * @var ChangePasswordRepository Dépôt lié à la bd permettant d'accéder aux usagers.
     */
    private $ChangePasswordRepository;

    /**
     * ChangePassword constructeur.
     * @param ChangePasswordRepository $ChangePasswordRepository Dépôt des usagers.
     */
    public function __construct(ChangePasswordRepository $_ChangePasswordRepository)
    {
        $this->ChangePasswordRepository = $_ChangePasswordRepository;
    }

    /**
     * Fonction permettant de changer le mot de passe d'un usager.
     * @param User L'usager
     * @param string le nouveau mot de passe
     * @return Result Retourne le résultat de l'opération.
     */
    public function update_pwd(string $email, string $pwd): Result
    {
        try {
            $res = $this->ChangePasswordRepository->update_pwd($email, $pwd);

            if (!$res) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Aucun mot de passe à changer"));
            }

            return new Result(EnumHttpCode::SUCCESS, array("Mot de passe changé!"), array("TRUE"));
        } catch (Exception $exception) {
            return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors du changement de mot de passe."));
        }
    }
}
?>
