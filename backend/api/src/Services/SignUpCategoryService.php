<?php

namespace App\Services;

use App\Repositories\Repository;
use App\Repositories\SignUpCategoryRepository;
use App\Enums\EnumHttpCode;
use App\Models\Result;
use PDOException;

/**
 * SignUpCategoryService
 * @author Tristan Lafontaine
 */
class SignUpCategoryService extends Repository
{
    /**
     * signUpCategoryRepository
     *
     * @var SignUpCategoryRepository Permet d'avoir assez à la classe SignUpCategoryRepository'
     */
    private $signUpCategoryRepository;
    
    /**
     * __construct
     * @param  mixed $signUpCategoryRepository
     * @return void
     */
    public function __construct(SignUpCategoryRepository $signUpCategoryRepository)
    {
        $this->signUpCategoryRepository = $signUpCategoryRepository;
    }
    
    /**
     * get_all_category
     * Function qui permet de vérifier si la requête SQL a bien fonctione
     * @return Result
     */
    public function get_all_category() : Result
    {
        try
        {
            $category = $this->signUpCategoryRepository->get_all_categories();
            return new Result(EnumHttpCode::SUCCESS, array("categories"), $category);
        }
        catch(PDOException $e)
        {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return new Result(EnumHttpCode::BAD_REQUEST, array("Il y a eu une erreur pour obtenir les categories"));
        }
    }

}