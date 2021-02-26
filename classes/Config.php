<?php
define('DB_NAME', 'ev_financeiro');
define('DB_HOST', 'localhost');
define('DB_USER','root');
define('DB_PASS', '');



class Config {

    public static function conect() {
    
        try {
            $pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
            return [true,$pdo];     
        } catch (PDOException $e) {
            return [false,$e->getMessage()];
        }    
    
    
    }


}









