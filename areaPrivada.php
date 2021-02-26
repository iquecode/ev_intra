<?php
    session_start();
    if(!isset($_SESSION['userId'])) {
        header("location: index.php");
        exit;
    }
?> 

<?php

    require_once 'UserDaoMysql.php';
    require_once 'classes/Config.php'; 



    function num($n)  // formata número
{
    $n = number_format($n, 2, ',', '.');
    return $n;
}



    $pdo = Config::conect();
    $userDao = new UserDaoMysql($pdo[1]);


    $id = $_SESSION['userId'];
    //echo "ID: ".$id;

    $u = $userDao->findById($id);
    
    $nickname = $u->getNickname();
    $quota = $u->getQuota();
    $name = $u->getName();
    
    echo "Seja bem vind@@@@ ".$nickname;
    echo "<br/>";
    echo "---------------------------------------------------------";
    echo "<br/>";
    echo "Cota ".$quota." - ".$name;
    echo "<br/>";
    echo " Extrato financeiro";
    echo "<br/>";
    echo "---------------------------------------------------------";
    echo "<br/>";

    //print_r($u);
    //echo $u->getName();

    
    $statement = $u->getEntrys();

    //print_r($statement);
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
    <link rel="stylesheet" href="css/style.css">      
</head>
<body>


    <div class="extrato">
        <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th class="num">Valor R$</th>
        </tr>
        <?php
        //gera extrato na tela
        foreach ($statement as $item) {         
            $date = date('d/m/Y',strtotime($item->getDate()));
            $description = $item->getDescription();
            $value = $item->getValue();   
            echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
            // echo $date.".....".$description.".....".num($value);
            //var_dump($item);
            // echo "<br/>";
            $total = $total + $value;
        }
        echo "</table>";
        echo "Saldo atual: ".num($total);
        ?>
    </div>



<br>
<br>
<br>
<br>
<a href="update.php"> Alterar senha e confirmar dados   -</a>  
<a href="sair.php">-    Sair </a>


</body>
</html>