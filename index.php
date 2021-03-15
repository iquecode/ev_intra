<?php
session_start();

spl_autoload_register( function($class) {
    if (file_exists('views/' . $class . '.php')) {
        require_once 'views/' . $class . '.php';
    }
});


$class = isset($_REQUEST['class']) ? $_REQUEST['class'] : null;
$metodo = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;

//print_r($class); 
if (!class_exists($class) || !isset($_SESSION['userId']) )  
{
    $class = 'LoginArea';
}
//print_r($class);
$pagina = new $class( $_REQUEST );

if (!empty($metodo) AND method_exists($class, $metodo))
{
    $pagina->$metodo( $_REQUEST );
}

$pagina->show();