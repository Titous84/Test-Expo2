<?php

namespace App\Validators;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Repositories\SignUpTeamRepository;

/**
 * ValidatorTeam
 * Permet de valider chaque champs d'un objet Team
 * @author Tristan Lafontaine
 * @package App\Validators
 */
class ValidatorTeam extends Validator
{    
    /**
     * signUpTeamRepository
     *
     * @var SignUpTeamRepository Permet d'avoir assez à la classe SignUpTeamRepository'
     */
    private $signUpTeamRepository;
    
    /**
     * __construct
     *
     * @param  mixed $signUpTeamRepository
     * @return void
     */
    public function __construct(SignUpTeamRepository $signUpTeamRepository)
    {
        $this->signUpTeamRepository = $signUpTeamRepository;
    }
 
    /**
     * validate
     * Permet de valider les champs d'une équipe
     * @param  mixed $team
     * @author Tristan Lafontaine
     * @return Result
     */
    public function validate( array $team ) : Result
    {
        //Tableau d'erreur de vérification
        $messages = [];

        //Vérication que le champ existe
        if(!isset($team['title'])){
            $messages[] = "Le titre est vide. ";
        }
        //Vérication que le champ existe
        if(!isset($team['description'])){
            $messages[] = "La description est vide. ";
        }
        //Vérication que le champ existe
        if(!isset($team['category'])){
            $messages[] = "La categorie est vide. ";
        }
        //Vérication que le champ existe
        if(!isset($team['year'])){
            $messages[] = "L'année est vide. ";
        }
        else{
            //Vérifiication du champs si il est vide ou non difinie.
            if($this->is_empty($team['title'])){
                $messages[] = "Le titre du stand est obligatoire.";
            }

            //Vérification si le champs description n'est pas vide
            if($this->is_empty($team['description'])){
                $messages[] = "La description du stand est obligatoire.";
            }
            
            //Vérification si le champs categorie n'est pas vide
            if($this->is_empty($team['category'])){
                $messages[] = "La categorie est obligatoire.";
            }

            //Vérification si le champs année n'est pas vide
            if($this->is_empty($team['year'])){
                $messages[] = "L'année est obligatoire.";
            }
            
            //Permet d'obtenir la longueur du tableau
            $sizeofArray = sizeof($team['contactPerson']);
            //Vérification si il y a au minimum une personne ressource présent.
            if($sizeofArray == 0){
                $messages[] = "Il doit avoir au minimun une personne-ressource.";
            }
            //Vérification qu'il a un maximum de deux personnes-ressources
            else if($sizeofArray > 2){
                $messages[] = "Il doit avoir au maximum deux personnes-ressources.";
            }
            else{
                //Boucle qui permet de changer de case dans le tableau
                for($a = 0; $a < $sizeofArray; $a++){
                    //Vérification si l'adresse courriel de la personne ressource n'est pas vide
                    if($this->is_empty($team['contactPerson'][$a]['email'])){
                        $messages[] = "L'adresse courriel de la personne-ressource est obligatoire : " . $team['contactPerson'][$a]['email'];
                    }

                    //Vérification de l'adresse courriel.
                    if($this->verify_email_contact_person($team['contactPerson'][$a]['email'])){
                        $messages[] = "L'adresse courriel est invalide : " . $team['contactPerson'][$a]['email'];
                    }

                    //Vérification si le prénom et le nom de la personne ressource n'est pas vide
                    if($this->is_empty($team['contactPerson'][$a]['fullName'])){
                        $messages[] = "Le prénom et le nom de la personne ressource est obligatoire : " . $team['contactPerson'][$a]['fullName'];
                    }
                }
            }

            //Permet d'obtenir la longueur du tableau
            $sizeofArray = sizeof($team['members']);
            //Nombre de membres maximum dans une équipe
            $membersMax = $this->signUpTeamRepository->get_max_members_category($team['category']);
            //Vérifie si la valeur est null
            if($membersMax == null){
                $messages[] = "La catégorie est incorrect.";
            }
            else{
                //Vérification si il y a au minimum deux membres de l'équipe.
                if($sizeofArray < 2){
                    $messages[] = "Il doit avoir minimum deux membres dans l'équipe.";
                }

                //Vérification du nombre maximum de membre d'équipe dans une équipe
                if($sizeofArray > $membersMax){
                    $messages[] = "Il doit avoir au maximum de ". $membersMax ." membres dans l'équipe";
                }

                for($a = 0; $a < $sizeofArray; $a++){
                    //Vérification du prénom du membre n'est pas vide
                    if($this->is_empty($team['members'][$a]['firstName'])){
                        $messages[] = "Le prénom est obligatoire : " . $team['members'][$a]['firstName'];
                    }

                    //Vérification du nom du membre n'est pas vide
                    if($this->is_empty($team['members'][$a]['lastName'])){
                        $messages[] = "Le nom est obligatoire : " . $team['members'][$a]['lastName'];
                    }
                    
                    // Vérification du numéro de DA du membre n'est pas vide.
                    if ($this->is_empty($team['members'][$a]['numero_da'])) {
                        $messages[] = "Le numéro DA du membre est obligatoire : " . $team['members'][$a]['numero_da'];
                    }
                }
            }
        }
        if(sizeof($messages) == 0 ) return new Result(EnumHttpCode::SUCCESS, array("Validation réussi"));
		return new Result(EnumHttpCode::BAD_REQUEST, array("Validation n'a pas réussi"), $messages);
	}
}