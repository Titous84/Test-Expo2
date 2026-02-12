<?php

namespace App\Repositories;

use PDO;
use PDOException;

/**
 * Classe InformationsRepository
 * @author Mathieu Sévégny
 * @package App\Repositories
 */
class InformationsRepository extends Repository
{
	/**
	 * Fonction permettant d'obtenir les informations qui sont enabled.
	 * @author Mathieu Sévégny et Gabriel Beaudoin
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|null Retourne les informations, sinon retourne null.
	 */
	public function get_informations()
	{
		try
		{

			$sql = "SELECT site_component.id, title,content,enabled, site_component.order from site_component
			INNER JOIN component_type on site_component.type_id = component_type.id
			where component_type.name = 'Informations' and enabled = true;";
			$req = $this->db->prepare($sql);

			$req->execute();

			$informations = $req->fetchAll();

			if(!is_array($informations))
			{
				return null;
			}

			return $informations;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}

	/**
	 * Fonction permettant d'obtenir les informations.
	 * @author Mathieu Sévégny et Gabriel Beaudoin
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return array|null Retourne les informations, sinon retourne null.
	 */
	public function get_informations_admin()
	{
		try
		{
			$sql = "SELECT site_component.id, title,content,enabled, site_component.order from site_component
			INNER JOIN component_type on site_component.type_id = component_type.id
			where component_type.name = 'Informations';";
			$req = $this->db->prepare($sql);

			$req->execute();

			$informations = $req->fetchAll();

			if(!is_array($informations))
			{
				return null;
			}

			return $informations;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}

	//Add an information block
	/**
	 * Fonction permettant de créer un nouveau bloc d'information.
	 * @author Mathieu Sévégny
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return bool|null Retourne les informations, sinon retourne null.
	 */
	public function create_information_block(string $title,string $content,int $order)
	{
		try
		{
			$type_id = $this->get_informations_type_id();
			if ($type_id == null)
			{
				return null;
			}
			$sql = "INSERT INTO site_component (`title`,`content`,`order`,`type_id`) VALUES (:title,:content,:order,:type_id);";
			$req = $this->db->prepare($sql);

			$req->execute(array(
				"title" => $title,
				"content" => $content,
				"order" => $order,
				"type_id" => $type_id
			));

			return $req->rowCount() == 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}
	/**
	 * Fonction permettant de chercher l'identifiant du type de composant d'informations.
	 * @author Mathieu Sévégny
	 * @return int|null Retourne l'identifiant du type de composant d'informations, sinon retourne null.
	 */
	public function get_informations_type_id()
	{
		try
		{
			$sql = "SELECT id from component_type where name = 'Informations';";
			$req = $this->db->query($sql);
			$response = $req->fetch();
			return !$response ? null : $response['id'];
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}
	/**
	 * Fonction permettant de supprimer un bloc d'information.
	 * @author Mathieu Sévégny
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return bool|null Retourne si le requête a fonctionné, sinon retourne null.
	 */
	public function remove_information_block($id)
	{
		try
		{
			$sql = "DELETE FROM site_component WHERE id = :id;";
			$req = $this->db->prepare($sql);

			$req->execute(array(
				"id" => $id
			));

			return $req->rowCount() == 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}
	/**
	 * Fonction permettant de mettre à jour un bloc d'information.
	 * @author Mathieu Sévégny
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return bool|null Retourne si le requête a fonctionné, sinon retourne null.
	 */
	public function update_information_block(int $id,string $title,string $content,bool $enabled)
	{
		try
		{
			$sql = "UPDATE site_component SET title = :title, content = :content, enabled = :enabled WHERE id = :id;";
			$req = $this->db->prepare($sql);

			$req->execute(array(
				"id" => $id,
				"title" => $title,
				"content" => $content,
				"enabled" => $enabled
			));

			return $req->rowCount() == 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}

	/**
	 * Fonction permettant de sauvegarder la position d'un bloc d'information.
	 * @author Mathieu Sévégny
	 * @throws PDOException Peut lancer des erreurs PDOException.
	 * @return bool|null Retourne si le requête a fonctionné, sinon retourne null.
	 */
	public function save_position_informations_blocks(int $id, int $order)
	{
		try
		{
			$sql = "UPDATE site_component SET `order` = :order WHERE id = :id";
			$req = $this->db->prepare($sql);

			$req->execute(array(
				"order" => $order,
				"id" => $id
			));

			return $req->rowCount() == 1;
		}
		catch(PDOException $e)
		{
			$context["http_error_code"] = $e->getCode();
            $this->logHandler->critical($e->getMessage(), $context);
			return null;
		}
	}
}