<?php

namespace App\Services;

use App\Enums\EnumHttpCode;
use App\Models\Result;
use App\Repositories\InformationsRepository;

/**
 * Classe InformationsService.
 * @package App\Services
 */
class InformationsService
{
	/**
	 * @var InformationsRepository Dépôt lié à la bd permettant d'accéder aux informations.
	 */
	public $informationsRepository;

	/**
	 * InformationsService constructeur.
	 * @author Mathieu Sévégny
	 * @param InformationsRepository $tokenRepository Dépôt des tokens.
	 */
	public function __construct(InformationsRepository $informationsRepository)
	{
		$this->informationsRepository = $informationsRepository;
	}

	/**
	 * Fonction qui permet d'obtenir les informations.
	 * @author Mathieu Sévégny et Gabriel Beaudoin
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_informations(): Result
	{
		$informations = $this->informationsRepository->get_informations();

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la recherche des informations !"), null);
		}

		return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les informations !"), $informations);
	}

	/**
	 * Fonction qui permet d'obtenir les informations pour l'admin.
	 * @author Mathieu Sévégny et Gabriel Beaudoin
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function get_informations_admin(): Result
	{
		$informations = $this->informationsRepository->get_informations_admin();

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la recherche des informations !"), null);
		}

		return new Result(EnumHttpCode::SUCCESS, array("Nous avons trouver les informations !"), $informations);
	}

	/**
	 * Fonction qui permet de créer un nouveau bloc d'information.
	 * @author Mathieu Sévégny
	 * @param string $title Titre du bloc d'information.
	 * @param string $content Contenu du bloc d'information.
	 * @param int $order Ordre du bloc d'information.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function create_information_block(string $title, string $content, int $order): Result
	{
		if ($title === null || $content === null || $order === null)
		{
			return new Result(EnumHttpCode::BAD_REQUEST, array("Veuillez remplir tous les champs !"), null);
		}

		$informations = $this->informationsRepository->create_information_block($title, $content, $order);

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la création du bloc d'information !"), null);
		}

		return new Result(EnumHttpCode::CREATED, array("Le bloc d'information a bien été créé !"), $informations);
	}

    /**
     * Fonction qui permet de supprimer un bloc d'information.
     * @author Mathieu Sévégny
     * @param int $id Id du bloc d'information.
     * @return Result Retourne le résultat de l'opération.
     */
	public function delete_information_block(int $id): Result
	{
		$informations = $this->informationsRepository->remove_information_block($id);

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la suppression du bloc d'information !"), null);
		}

		return new Result(EnumHttpCode::SUCCESS, array("Le bloc d'information a bien été supprimé !"), $informations);
	}
	//Update an information block
	/**
	 * Fonction qui permet de modifier un bloc d'information.
	 * @author Mathieu Sévégny
	 * @param int $id Id du bloc d'information.
	 * @param string $title Titre du bloc d'information.
	 * @param string $content Contenu du bloc d'information.
	 * @param int $order Ordre du bloc d'information.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function update_information_block(int $id, string $title, string $content, bool $enabled): Result
	{
		$informations = $this->informationsRepository->update_information_block($id, $title, $content, $enabled);

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la modification du bloc d'information !"), null);
		}

		return new Result(EnumHttpCode::SUCCESS, array("Le bloc d'information a bien été modifié !"), $informations);
	}

	/**
	 * Fonction qui permet de modifier l'ordre d'un bloc d'information.
	 * @author Mathieu Sévégny
	 * @param int $id Id du bloc d'information.
	 * @param int $order Ordre du bloc d'information.
	 * @return Result Retourne le résultat de l'opération.
	 */
	public function update_information_block_order(int $id, int $order): Result
	{
		$informations = $this->informationsRepository->save_position_informations_blocks($id, $order);

		if ($informations === null)
		{
			return new Result(EnumHttpCode::SERVER_ERROR, array("Nous avons eu un problème lors de la modification de l'ordre du bloc d'information !"), null);
		}

		return new Result(EnumHttpCode::SUCCESS, array("L'ordre du bloc d'information a bien été modifié !"), $informations);
	}

}