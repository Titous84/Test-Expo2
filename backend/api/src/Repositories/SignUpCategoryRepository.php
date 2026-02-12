<?php

namespace App\Repositories;

use PDOException;

/**
 * SignUpCategoryRepository
 * @author Tristan Lafontaine
 */
class SignUpCategoryRepository extends Repository{
    
    /**
     * getAllCategory
     * Obtien la liste des catégories
     * @return array
     */
    public function get_all_categories() : array
    {
        try
        {
            $sql = "SELECT id, name, max_members, acronym FROM categories WHERE activated = 1";
            $req = $this->db->prepare($sql);
            $req->execute();
            return $req->fetchAll();
        }
        catch(PDOException $e)
        {
            $context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return [];
        }
    }

}