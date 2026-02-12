<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Handlers\LogHandler;
use App\Models\Result;
use App\Models\VerificationCode;
use App\Validators\ValidatorVerificationCode;
use App\Repositories\VerificationCodeRepository;
use App\Utils\GeneratorUUID;
use Exception;

/**
 * Classe VerificationCodeService.
 * Fortement inspiré de UserService
 * @author MaximeDemersBoucher
 * @package App\Services
 */
class VerificationCodeService
{
	/**
	 * @var VerificationCodeRepository Dépôt lié à la bdd permettant d'accéder aux codes de vérification.
	 */
	private $verificationCodeRepository;

	/**
	 * @var LogHandler Gestionnaire de log.
	 */
	private $logHandler;

	
	/**
	 * @var ValidatorVerificationCode Validateur permettant de vérifier les données liés au code de vérification.
	 */
	private $validatorUserRole;

	/**
     * generatorUUID
     *
     * @var GeneratorUUID Permet d'avoir assez à la classe GeneratorUUID
     */
    private $generatorUUID;

	/**
	 * VerificationCodeService constructeur.
	 * @param VerificationCodeRepository $verificationCodeRepository Dépôt des codes de vérification.
	 * @param ValidatorVerificationCode $validatorVerificationCode validateur des codes de vérification.
	 * @param LogHandler $logHandler Permet d'enregistrer les erreurs.
	 */
	public function __construct( VerificationCodeRepository $verificationCodeRepository, LogHandler $logHandler, ValidatorVerificationCode $validatorVerificationCode,GeneratorUUID $generatorUUID)
    {
        $this->verificationCodeRepository = $verificationCodeRepository;
		$this->validatorVerificationCode = $validatorVerificationCode;
		$this->generatorUUID = $generatorUUID;
		$this->logHandler = $logHandler;
    }

	/**
	 * Fonction qui permet d'ajouter un code de vérification.
	 * @author Maxime Demers Boucher
	 * @param string $email email qui sera lier au code de vérification
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
    public function generate_code(string $email): Result
    {
	    try
	    {
			//On vérifie les données reçues par l'utilisateur (seulement l'email)
			$verificationCodeResult = $this->validatorVerificationCode->validate($email);

			//Si des erreurs surviennent, on les affiche.
		    if( $verificationCodeResult->get_http_code() != EnumHttpCode::SUCCESS )
		    {
			    return $verificationCodeResult;
		    }
			$verificationCode = $this->generatorUUID->generate_single_UUID();

			//On créer un code de vérification troisième valeure est l'heure actuelle plus 15 minute
			$code = new VerificationCode(substr($verificationCode,24),$email,date('c',(time()+900)));


			//On essaie d'ajouter le code dans la bd
		    $resultAddCodeVerification = $this->verificationCodeRepository->add_code($code);

			//gestion erreur
		    if( $resultAddCodeVerification === null )
			{
			    return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'ajout du code"));
		    }
			return new Result(EnumHttpCode::SUCCESS, array("Le code a été ajouté !"),$resultAddCodeVerification);
	    }
		catch ( Exception $e )
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
    }

	/**
	 * Fonction qui permet de validé un code de vérification
	 * @author Maxime Demers Boucher
	 * fortement inspiré de userService
	 * @param string $code le code a vérifier
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
    public function verify_code(string $code): Result
    {
	    try
	    {
			//On vérifie si le code est présent dans la bd
			$verificationCodeResult = $this->verificationCodeRepository->validate_code($code);

			//Si des erreurs surviennent, on les affiche.
		    if( $verificationCodeResult === false )
		    {
				return new Result(EnumHttpCode::SERVER_ERROR, array("Le code est expiré ou n'est pas présent dans le system"));
		    }
			return new Result(EnumHttpCode::SUCCESS, array("Le code est valide"),$verificationCodeResult);
	    }
		catch ( Exception $e )
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
    }

	/**
	 * Fonction qui permet de suprimer un code de vérification
	 * @author Maxime Demers Boucher
	 * fortement inspiré de userService
	 * @param VerificationCode $code de vérification
	 * @return Result Retourne un objet de type Result contenant le résultat de l'opération.
	 */
    public function delete_code(string $email): Result
    {
	    try
	    {
			//On vérifie si le code est présent dans la bd
			$verificationCodeDeleted = $this->verificationCodeRepository->delete_code($email);

			//Si des erreurs surviennent, on les affiche.
		    if( $verificationCodeDeleted === false )
		    {
			    return new Result(EnumHttpCode::SUCCESS, array("Une erreur est survenue lors de la suppression"),$verificationCodeDeleted);
		    }
			return new Result(EnumHttpCode::SUCCESS, array("Le code a été supprimé"),$verificationCodeDeleted);
	    }
		catch ( Exception $e )
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue."));
		}
    }
}