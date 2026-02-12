<?php

namespace App\Services;
use App\Enums\EnumHttpCode;
use App\Models\Credential;
use App\Models\Result;
use App\Repositories\StandRepository_ss;


/**
  * Souleymane Soumaré
 * Service pour obtenir stands.
 */
final class StandService_ss
{
    /**
     * @var StandRepository_ss
     */
    private $repository;

    /**
     * Le constructeur.
     *
     * @param StandRepository_ss $repository The repository
     */
    public function __construct(StandRepository_ss $repository)
    {
        $this->repository = $repository;
    }

    public function showingStands() : Result
    {
        $resultats = $this->repository->selectStands();
        return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les resultats !"), $resultats);
    }

}

