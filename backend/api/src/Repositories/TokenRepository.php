<?php

namespace App\Repositories;

use App\Handlers\LogHandler;
use App\Models\Credential;
use DateTimeImmutable;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use PDOException;
use PDO;

/**
 * Classe TokenRepository
 * @package App\Repositories
 */
class TokenRepository extends Repository
{
	/**
	 * @var Configuration Objet de configuration des JWT.
	 */
	private $configuration;

	/**
	 * TokenRepository constructeur.
	 */
	public function __construct(PDO $db, LogHandler $logHandler)
	{
		parent::__construct($db, $logHandler);
		$this->configuration = Configuration::forSymmetricSigner(
			new Sha256(),
			InMemory::base64Encoded($_ENV["token_key"])
		);
		$this->configuration->setValidationConstraints(new SignedWith($this->configuration->signer(), $this->configuration->signingKey()));
	}

	/**
	 * Fonction permettant d'obtenir un token.
	 *
	 * @param Credential $identifiant Identifiants fournies par l'utilisateur.
	 *
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return string|null Retourne le token, sinon retourne null.
	 */
	public function get_token( Credential $credential )
	{

		$sql = "SELECT id, role_id, pwd FROM users WHERE email = :email";
		$req = $this->db->prepare($sql);

		$resultGetUserByEmail = $req->execute(array(
			"email" => $credential->email
		));

		$user_data = $req->fetch();
		
		if(!is_array($user_data))
		{
			return null;
		}

		$now = new DateTimeImmutable();

		if(!$resultGetUserByEmail || !password_verify($credential->password, $user_data["pwd"]))
		{
			return null;
		}

		return $this->configuration->builder()
			// Configures the issuer (iss claim)
			->issuedBy($_ENV["base_url"])
			// Configures the audience (aud claim)
			->permittedFor($_ENV["base_url"])
			// Configures the id (jti claim)
			->identifiedBy($user_data["id"])
			// Configures the time that the token was issue (iat claim)
			->issuedAt($now)
			// Ajouter un rôle
			->withClaim("role_id", $user_data["role_id"])
			// Configures the time that the token can be used (nbf claim)
			// Cette fonction permet d'expirer le token, mais permettre qu'il soit étendu.
			->canOnlyBeUsedAfter($now->modify('+1 minute'))
			// Configures the expiration time of the token (exp claim)
			// Cette fonction est la limite absolu de durée du token.
			->expiresAt($now->modify('+1 hour'))
			// Builds a new token
			->getToken($this->configuration->signer(), $this->configuration->signingKey())->toString();
	}

	/**
	 * Fonction qui permet de vérifier un token.
	 * @param string $token_string Token à vérifier.
	 * @return bool Retourne vrai si le token est valide et faux dans le cas contraire.
	 */
	public function verify_token(string $token_string)
	{
		try
		{
			$token = $this->configuration->parser()->parse($token_string);
			$constraints = $this->configuration->validationConstraints();

			if (! $this->configuration->validator()->validate($token, ...$constraints))
			{
				return false;
			}

			return true;
		}	
		catch(Exception $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return false;
		}
	}

	/**
	 * Fonction qui permet d'obtenir les claims dans le Token.
	 * @param string $token_string Token.
	 * @param string $claim_name Nom de la claim créer lors de la signature.
	 * @return mixed|null Peut retourner un objet stocké dans la claim ou retourner null.
	 */
	public function get_claim(string $token_string, string $claim_name)
	{
		try
		{
			$token = $this->configuration->parser()->parse($token_string);
			return $token->claims()->get($claim_name);
		}
		catch(Exception $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}

	/**
	 * Fonction qui permet d'avoir le nom du rôle à partir de l'id du rôle.
	 * @param int $role_id Id du rôle.
	 * @return string|null Retourne le nom du rôle, sinon retourne null.
	 */
	public function get_role_name(int $role_id)
	{
		try
		{
			$sql = "SELECT name FROM role WHERE id = :role_id";
			$req = $this->db->prepare($sql);

			$req->execute(array(
				"role_id" => $role_id
			));

			$response = $req->fetch();
			return !$response ? null : $response["name"];
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}
}