<?php

spl_autoload_register( function($class) {
    if (file_exists('views/' . $class . '.php')) {
        require_once 'views/' . $class . '.php';
    }
});

$classe = isset($_REQUEST['class']) ? $_REQUEST['class'] : null;
$metodo = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;

if (class_exists($classe))
{
    $pagina = new $classe( $_REQUEST );
    
    if (!empty($metodo) AND method_exists($classe, $metodo))
    {
        $pagina->$metodo( $_REQUEST );
    }
    $pagina->show();
}
else
{
    header("location: index.php?class=LoginPage");
    exit;
}


