<?php

namespace App\Repositories;

use App\Enums\EnumHttpCode;
use App\Models\User;
use App\Models\Result;
use PDOException;

/**
 * Classe ChangePasswordRepository.php
 * @package App\Repositories
 * @author Samuel Lambert
 */

class ChangePasswordRepository extends Repository
{    
    //Tableau de messages d'erreur
    private $errorMessages = [];

    /**
     * update_pwd
     * Change le mot de passe d'un user
     * @param User $user Le user à qui on veut changer le mot de passe 
     * @return Result
     */
    public function update_pwd(string $email, string $pwd):bool
    {
        try{
            
            $sql = "UPDATE users SET pwd = :pwd WHERE email = :email and role_id = 0";
            $req = $this->db->prepare($sql);
            $req->execute(
                array(
                    'pwd' => password_hash($pwd, PASSWORD_DEFAULT),
                    'email'  => $email,
                )
            );
            return $req->rowCount() === 1;
        }
        catch(PDOException $e) {
            $this->errorMessages[] = "updatepwd: " . $e->getMessage();
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
            return false;
        }
    }
}