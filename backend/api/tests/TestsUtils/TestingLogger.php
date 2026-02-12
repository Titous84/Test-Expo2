<?php
namespace Test\TestsUtils;

/**
 * Fonctions d'aide pour l'affichage de messages lors des tests.
 * @author Raphaël Nadeau
 */
class TestingLogger {
    /**
     * Écrit un message dans la console de test contenant la date et l'heure et un retour à la ligne.
     * Ne devrait pas être utilisée hors des classes de tests!
     * @param string $message L'information à afficher après la date et l'heure.
     */
    public static function log($message) {
        echo "[".date("Y-m-d h:m:s")."]: " . $message . "\n";
    }
}