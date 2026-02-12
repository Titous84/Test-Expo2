<?php
namespace App\Utils;

use App\Models\Team;

class GeneratorUUID{
     /**
     * generateUUID
     * Fonction qui genere un UUID
     * @return array
     * @author Tristan Lafontaine
     * @source https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     */
    public static function generate_UUID_array(int $number) : array
    {
        $token = [];

        for($a = 0; $a < $number; $a++){
            $data = random_bytes(16);
            assert(strlen($data) == 16);

            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

            $token[] = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        return $token;
    }
    
    /**
     * generate_single_UUID
     * Fonction qui génère un UUID
     * @return string
     * @author Jean-Philippe Bourassa
     * @source https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
     */
    public static function generate_single_UUID() : string
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}