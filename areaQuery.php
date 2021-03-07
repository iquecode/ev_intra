<?php
    if( $_POST['cota'] == '') 
    {
        header("location: areaPrivada.php");
        exit;
    } 

    require_once 'UserDaoMysql.php';
    require_once 'classes/Config.php'; 
    require_once 'helper.php';

    $pdo = Config::conect();
    $userDao = new UserDaoMysql($pdo[1]);
    $u = $userDao->findByQuota($_POST['cota']);

    // se não encontrar usuário com o número de cota informado
    if(!$u) {
        header("location: areaPrivada.php");
        exit;
    }
    
    $id = $u->getId();
    $nickname = $u->getNickname();
    $quota = $u->getQuota();
    $name = $u->getName();
    $isAdmin = ((int)$u->getType()) == 1;
    $statement = $u->getEntrys();
    $total = 0;

    // Ordena os lançamentos por data
    usort($statement, function($a, $b){ return $a->getDate() >= $b->getDate(); });
    ?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Ecovileir@</title>
    <link rel="stylesheet" href="css/style_content.css?v=<?= filemtime('css/style.css'); ?>">     
</head>
<body>

    <div class="layout">

    <div class = "cabecalho">
        <?php
        echo "TELA DE CONSULTA ADMINISTRATIVA";
        echo "<br/>";
        echo "---------------------------------------------------------";
        echo "<br/>";
        echo "Cota ".$quota." - ".$nickname." - ".$name." - Id: ".$id;
        echo "<br/>";
        echo "---------------------------------------------------------";
        ?>
    </div>

    <?php require_once 'views/stat.php' ?>
    <?php require_once 'views/futures.php' ?>
    
<div class ="opcoes">
    <a href="areaPrivada.php"> Voltar</a>  
    <a href="sair.php">Sair </a>
</div>

</div>

</body>
</html>