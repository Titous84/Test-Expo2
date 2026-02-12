<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Models\Team;
use App\Repositories\SignUpTeamRepository;
use App\Validators\ValidatorTeam;
use App\Fabricators\Emails\EmailValidationFabricator;
use App\Fabricators\Emails\EmailContactPersonFabricator;
use App\Services\EmailService;
use App\Services\TwigService;
use App\Utils\GeneratorUUID;
use Exception;

/**
 * SignUpTeamService
 * @author Tristan Lafontaine
 */
class SignUpTeamService{

    /**
	 * @var SignUpTeamRepository Dépôt lié à la bdd permettant d'accéder à l'inscription.
	 */
	private $signUpTeamRepository;
    
    /**
     * validatorTeam
     *
     * @var ValidatorTeam Permet d'avoir assez à la classe ValidatorTeam
     */
    private $validatorTeam;
    
    /**
     * emailService
     *
     * @var EmailService Permet d'avoir assez à la classe EmailService'
     */
    private $emailService;
    
    /**
     * twigService
     *
     * @var TwigService Permet d'avoir assez à la classe TwigService'
     */
    private $twigService;
    
    /**
     * generatorUUID
     *
     * @var GeneratorUUID Permet d'avoir assez à la classe GeneratorUUID
     */
    private $generatorUUID;
	/**
	 * __construct
	 * @param  mixed $signUpTeamRepository
	 * @param  mixed $validatorTeam
	 * @return void
	 */
	public function __construct( SignUpTeamRepository $signUpTeamRepository, ValidatorTeam $validatorTeam, EmailService $emailService, TwigService $twigService )
    {
        $this->signUpTeamRepository = $signUpTeamRepository;
        $this->validatorTeam = $validatorTeam;
        $this->emailService = $emailService;
        $this->twigService = $twigService;
    }
    
    /**
     * addSignupTeam
     * Ajouter une équipe
     * @param  mixed $teamJson
     * @return Result
     */
    public function add_signup_team( array $teamJson ): Result
    {
        try
	    {
            
            $teamArray = $teamJson["team"];
            
            // Validation des champs
            $resultTeam = $this->validatorTeam->validate($teamArray);
            
            //Vérification des erreurs de validation des champs
            if($resultTeam->get_http_code() != 200)
            {
                return $resultTeam;
            }

            //Création d'un objet Team
            $team = new Team($teamArray);

            // Vérification si le numéro de DA n'est pas inscrit deux fois dans le formulaire
            $verificationDuplicateNumeroDa = $this->signUpTeamRepository->check_numero_da_duplicate($team);

            // Lever une exception si le numéro de DA est dupliqué.
            if (sizeof($verificationDuplicateNumeroDa) != 0) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Vous utilisez le même numéro de DA"), $verificationDuplicateNumeroDa);
            }

            // Vérification des numéros de DA des membres
            $verificationMember = $this->signUpTeamRepository->check_numero_da_is_not_BD($team);

            // Vérification si un numéro de DA est déjà présent dans la bd.
            if (sizeof($verificationMember) != 0) {
                return new Result(EnumHttpCode::BAD_REQUEST, array("Le numéro de DA est déjà utilisé"), $verificationMember);
            }

            //Vérifie si l'équipe est activé
            $teamActive = $this->signUpTeamRepository->check_team_active($team);
            
            $messageErreur = [];

            //Vérification si l'équipe est activé
            for($t = 0; $t < sizeof($teamActive); $t++){
                if($teamActive[$t] != false){
                    $messageErreur[] = $teamActive[$t]["first_name"] . " " . $teamActive[$t]["last_name"] . ", vous devez activer votre compte avant de créer une nouvelle équipe.";
                }
            }
            //Retourne une erreur si un membre de l'équipe n'a pas activé son autre équipe
            if(sizeOf($messageErreur) != 0){
                return new Result(EnumHttpCode::BAD_REQUEST, array("L'équipe n'est pas activé"), $messageErreur);
            }
            
            //Générer les tokens pour chaque membre
            $token = GeneratorUUID::generate_UUID_array(sizeOf($team->members));
            
            //Ajout d'une équipe dans la bd
            $resultSQL = $this->signUpTeamRepository->add_team($team,$token);

            //Vérification des erreurs lors de l'ajout de l'équipe
            if($resultSQL->get_http_code() == 400){
                return $resultSQL;
            }
            
            //Envoie d'email aux enseignants ressources
            $sizeofArray = sizeof($team->contactPerson);
            for($a = 0; $a < $sizeofArray; $a++){
               $sendMail = new EmailContactPersonFabricator($this->emailService,$this->twigService);
               $sendMail->send_mail_contact_person($team->contactPerson[$a]["fullName"], $team->contactPerson[$a]["email"], $team->members, $team->title, $team->description, $team->category, $team->year);
            }

            return new Result(EnumHttpCode::CREATED, array("Ajout réussi"), array("Ajout réussi"));
	    }
		catch ( Exception $e )
		{
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return new Result(EnumHttpCode::SERVER_ERROR, array("Une erreur inattendue est survenue.", $e->getMessage()));
		}
    }
    
}