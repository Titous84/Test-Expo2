<?php

namespace Test\Services\Informations;

use \App\Enums\EnumHttpCode;
use App\Handlers\LogHandler;
use \App\Services\InformationsService;
use \App\Repositories\InformationsRepository;
use \PHPUnit\Framework\TestCase;
use \Symfony\Component\Dotenv\Dotenv;
use \Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;
/**
 * Classe permettant de tester le service des informations.
 * @author Mathieu Sévégny
 * @package Services\InformationsRepositoryTest
 */
class InformationsServiceTest extends TestCase {
    
	private $pdo;
	private $informationRepository;
	private $informationService;
	private $mock = [
		'id' => 1,
		'title' => 'Titre',
		'content' => 'Contenu',
		'order' => -5,
		'enabled' => 1
	];
	/**
	 * @before
	 */
	public function set_up_environment()
	{
		//Configuration de l'environnement
		$dotenv = new Dotenv();
		$dotenv->load(__DIR__ . '/../../../.env.prod');
		$_ENV["production"] = "false";
		$this->pdo = new PDOInitialize();
		$this->informationRepository = new InformationsRepository($this->pdo->PDO(), new LogHandler());
		$this->informationService = new InformationsService($this->informationRepository);
	}
	/**
	 * Finds the first index that contains the specific order.
	 * @author Mathieu Sévégny
	 * @return int
	 */
	function find_id_in_informations_block(int $order) {
		TestingLogger::log("Recherche du bloc d'informations avec l'ordre : " . $order);
		$blocks = $this->informationRepository->get_informations_admin();
		TestingLogger::log("Blocks dans la base de données : " . json_encode($blocks));
		foreach($blocks as $block){
			if($block['order'] == $order){
				return $block['id'];
			}
		}
		return -1;
	}
	/**
	 * Teste la création d'un bloc d'informations.
	 * @author Mathieu Sévégny
	 * @return void
	 */
	public function test_add_information_block(){
		
		TestingLogger::log("Insertion du bloc d'informations");
        $response = $this->informationService->create_information_block(
			$this->mock["title"],$this->mock["content"],$this->mock["order"]);

		$this->assertEquals(EnumHttpCode::CREATED,$response->get_http_code(),"Erreur : test_create_information_block");
	}

	/**
	 * Teste la modification d'un bloc d'informations.
	 * @author Mathieu Sévégny
	 * @return void
	 */
	public function test_update_information_block(){
		$id = $this->find_id_in_informations_block($this->mock["order"]);
		if ($id == -1){
			$this->fail("Erreur : Le bloc n'a pas été trouvé");
			return;
		}

		TestingLogger::log("Mise à jour du bloc d'informations");
        $response = $this->informationService->update_information_block($id, "Title Test", 
							$this->mock["content"], $this->mock["enabled"]);

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_update_information_block");
	}

	/**
	 * Teste la modification de l'ordre d'un bloc d'informations.
	 * @author Mathieu Sévégny
	 * @return void
	 */
	public function test_update_information_block_order(){
		$id = $this->find_id_in_informations_block($this->mock["order"]);
		if ($id == -1){
			$this->fail("Erreur : Le bloc n'a pas été trouvé");
			return;
		}

		TestingLogger::log("Mise à jour de l'ordre du bloc d'informations");
        $response = $this->informationService->update_information_block_order($id,-4);

		$id = $this->find_id_in_informations_block(-4);
		if ($id == -1){
			$this->fail("Erreur : Le bloc n'a pas été trouvé");
			return;
		}
		$response = $this->informationService->update_information_block_order($id,-5);

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_update_information_block_order");
	}

	/**
	 * Teste la suppression d'un bloc d'informations.
	 * @author Mathieu Sévégny
	 * @return void
	 */
	public function test_remove_information_block(){
		$id = $this->find_id_in_informations_block($this->mock["order"]);
		if ($id == -1){
			$this->fail("Erreur : Le bloc n'a pas été trouvé");
			return;
		}

		TestingLogger::log("Suppression du bloc d'informations");
        $response = $this->informationService->delete_information_block($id);

		$this->assertEquals(EnumHttpCode::SUCCESS,$response->get_http_code(),"Erreur : test_delete_information_block");
	}

}