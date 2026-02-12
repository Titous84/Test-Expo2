<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Email;
use App\Models\Result;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Classe EmailService permet d'envoyer des courriels.
 * @author Christopher Boisvert
 * @package App\Services
 */
class EmailService
{
	/**
	 * @var PHPMailer Permet d'envoyer des courriels.
	 */
	public $phpMailer;

	/**
	 * EmailService constructeur.
	 *
	 * @param PHPMailer $phpMailer
	 */
	//TODO: $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
	public function __construct( PHPMailer $phpMailer )
	{
		$this->phpMailer = $phpMailer;
		$this->phpMailer->XMailer = " ";

		$this->phpMailer->isSMTP();
		$this->phpMailer->Host       = $_ENV["name_mail_server"];
		$this->phpMailer->SMTPAuth   = 1;
		$this->phpMailer->Username   = $_ENV["user_mail_server"];
		$this->phpMailer->Password   = $_ENV["password_mail_server"];
		$this->phpMailer->SMTPSecure = 'STARTTLS';
		$this->phpMailer->Port       = 587;
		$this->phpMailer->CharSet = 'UTF-8';
		$this->phpMailer->smtpConnect();
	}

	/**
	 * Fonction qui prend un objet courriel et envoie celui-ci.
	 *
	 * @param Email $email Courriel à envoyer.
	 *
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function send_mail( Email $email ): Result
	{
		try
		{
			$this->phpMailer->setFrom($_ENV["user_mail_server"], 'Ne pas répondre');
			$this->phpMailer->addAddress($email->receiver, $email->get_full_name());
			$this->phpMailer->addReplyTo($_ENV["user_mail_server"], 'Ne pas répondre');
			$this->phpMailer->Subject = $email->subject;
			$this->phpMailer->Body = $email->text_content;

			if($email->is_html())
			{
				$this->phpMailer->isHTML(true);
				$this->phpMailer->Body = $email->html_content;
				$this->phpMailer->AltBody = $email->text_content;
			}

		    if( $this->phpMailer->send() ){
				$this->phpMailer->clearAddresses();
				return new Result(EnumHttpCode::SUCCESS, array("Le courriel a bel et bien été envoyé !"));
			}
			else{
				return new Result(EnumHttpCode::SERVER_ERROR, array($this->phpMailer->ErrorInfo));
			}

			//return new Result(EnumHttpCode::SERVER_ERROR, array("Le courriel n'a pas été envoyé !"));
		}
		catch (Exception $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue lors de l'envoi du courriel."));
		}
	}
}