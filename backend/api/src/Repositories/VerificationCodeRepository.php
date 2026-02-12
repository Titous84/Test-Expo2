<?php

namespace App\Repositories;

use App\Models\VerificationCode;
use App\Utils\GeneratorUUID;
use Error;
use PDOException;
use Throwable;

/**
 * Classe UserRepository
 * @author Maxime Demers Boucher
 * @package App\Repositories
 */
class VerificationCodeRepository extends Repository
{
	/**
	 * Fonction qui permet d'ajouter un code.
	 * @author Maxime Demers Boucher
	 * @param VerificationCode $code
	 * @return int Retourne le code de vérification
	 */
    public function add_code( VerificationCode $code ) : string
    {
		try
		{
			$sql = "INSERT INTO code_verification(codeVerification,email,tempsAjout) 
                VALUES(:codeVerification,:email,:tempsAjout)";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"codeVerification" => $code->verificationCode,
				"email" => $code->email,
				"tempsAjout" => $code->valideTime,
			));
			return $code->verificationCode;
		}
		catch(PDOException $e)
		{
			var_dump($e);
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
    }

	/**
	 * Fonction qui permet d'ajouter un code.
	 * @author Maxime Demers Boucher
	 * @param string $code
	 * @return string|null Retourne l'email de verification.
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function validate_code( string $code )
    {
		try
		{
			$sql = "SELECT email FROM code_verification WHERE codeVerification = :codeVerification AND tempsAjout > CURRENT_TIMESTAMP";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"codeVerification" => $code
			));
			$response = $req->fetch();
			return !$response ? null : $response["email"];
		}
		catch(PDOException $e)
		{
			var_dump($e);
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
    }

		/**
	 * Fonction qui permet de supprimer un code
	 * @author Maxime Demers Boucher
	 * @param string $email
	 * @return int Retourne le nombre de lignes supprimer
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 */
    public function delete_code( string $email ):int
    {
		try
		{
			$sql = "DELETE FROM code_verification WHERE email=:email";
			$req = $this->db->prepare($sql);
			$req->execute(array(
				"email" => $email
			));
			return $req->rowCount();
		}
		catch(PDOException $e)
		{
			var_dump($e);
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return 0;
		}
    }
}
