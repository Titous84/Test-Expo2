<?php

namespace Repositories\ResultatRepository;

use App\Repositories\ResultatRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Test\TestsUtils\PDOInitialize;
use Test\TestsUtils\TestingLogger;
use PDO;

/**
 * Classe de test pour ResultatRepository.
 * Vérifie le bon fonctionnement de la récupération des résultats.
 * 
 * @author de base inconnu, le fichier était déja commencé
 * @editor Francis PAYAN
 */
final class ResultatRepositoryTest extends TestCase
{
    private $pdo;
    private $resultatRepository;

    /**
     * Configuration initiale pour l'ensemble de la classe de test.
     * Initialise l'environnement de test et les dépendances.
	 * 
	 * @author de base inconnu, le fichier était déja commencé
 	 * @editor Francis PAYAN
     */
    public static function setUpBeforeClass() : void
    {
        TestingLogger::log("Debut du setup de ResultatRepositoryTest");
        TestingLogger::log("Setup des variables d'environnement");
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env.prod');
    }

    /**
     * Setup avant chaque méthode de test.
     * Initialise les mocks nécessaires pour les tests.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    protected function setUp(): void
    {
        parent::setUp(); // Appel à la méthode parente setUp.
        $this->pdo = $this->createMock(PDO::class); // Initialisation de $pdo en tant que mock.
        $this->resultatRepository = new ResultatRepository($this->pdo); // Utilisation de $pdo pour instancier ResultatRepository.
    }

    /**
     * Teste si selectResultats retourne un tableau avec les données attendues.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    public function testSelectResultatsReturnsArray()
    {
        TestingLogger::log("Test de selectResultats pour vérifier le retour des données");
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([
            ['categorie' => 'Science', 'teams_name' => 'Team A', 'global_score' => 9.5],
            ['categorie' => 'Math', 'teams_name' => 'Team B', 'global_score' => 8.0]
        ]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $results = $this->resultatRepository->selectResultats();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertArrayHasKey('categorie', $results[0]);
        $this->assertEquals('Science', $results[0]['categorie']);
    }

    /**
     * Teste la gestion des exceptions dans selectResultats.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    public function testSelectResultatsHandlesExceptions()
    {
        TestingLogger::log("Test de selectResultats pour la gestion des exceptions");
        $this->pdo->method('prepare')->will($this->throwException(new \PDOException("Erreur de connexion à la base de données")));

        try {
            $this->resultatRepository->selectResultats();
            $this->fail("Une exception aurait dû être levée en raison d'une erreur de base de données");
        } catch (\PDOException $e) {
            $this->assertStringContainsString("Erreur de connexion à la base de données", $e->getMessage());
        }
    }

    /**
     * Teste selectResultats quand aucun résultat n'est retourné.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    public function testSelectResultatsWithNoResults()
    {
        TestingLogger::log("Test de selectResultats avec zéro résultat");
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $results = $this->resultatRepository->selectResultats();

        $this->assertIsArray($results);
        $this->assertEmpty($results, "Erreur : Le tableau de résultats devrait être vide");
    }

    /**
     * Teste selectResultats avec des données invalides.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    public function testSelectResultatsWithInvalidData()
    {
        TestingLogger::log("Test de selectResultats avec données invalides");
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([['invalid_field' => 'data']]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $results = $this->resultatRepository->selectResultats();

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertArrayNotHasKey('categorie', $results[0], "Erreur : La clé 'categorie' ne devrait pas exister dans les résultats");
    }

    /**
     * Teste selectResultats avec une simulation d'erreur SQL.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    public function testSelectResultatsSqlError()
    {
        TestingLogger::log("Test de selectResultats avec erreur SQL");
        $this->pdo->method('prepare')->will($this->throwException(new \PDOException("Erreur SQL")));

        try {
            $this->resultatRepository->selectResultats();
            $this->fail("Une exception PDOException aurait dû être levée à cause d'une erreur SQL");
        } catch (\PDOException $e) {
            $this->assertEquals("Erreur SQL", $e->getMessage());
        }
    }

	/**
     * Nettoyage après l'exécution de tous les tests de la classe.
	 * 
	 * @author Francis PAYAN
	 * Code généré par ChatGPT
	 * @see https://www.chatgpt.com/
     */
    protected function tearDown(): void
    {
        parent::tearDown(); // Appel à la méthode parente tearDown.
        $this->pdo = null; // Nettoyage de $pdo après chaque test.
    }
}