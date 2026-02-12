<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Credential;
use App\Models\Result;
use App\Repositories\SendRepository;
use App\Fabricators\Emails\EmailSendResultFabricator;


/**
 * Souleymane Soumaré
 * Service pour envoyer resultat par mail.
 */
final class ResultatSendService
{
    /**
     * @var SendRepository
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param SendRepository $repository The repository
     */
    public function __construct(SendRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sendingResultat($id) : string
    {   
        return json_encode($this->repository->sendResultats($id));
    }

    public function sendingMail($team,$note,$name,$email) 
    {       
        $sendEmail = new EmailSendResultFabricator($this->emailService,$this->twigService);
        $sendEmail->send_mail($team,$name,$note,$email);
    }   

}
