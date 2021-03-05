<?php
    //  session_start();
    //  if(!isset($_SESSION['queryId'])) {
    //      header("location: index.php");
    //      exit;
    //  }

    // if (isset($_POST['cota'])) {

    //     $id = $userDao->findByQuota($_POST['cota'])->getId();
    //     $_SESSION['queryId'] = $id;
    //     header("location: areaQuery.php");  
    //     exit;
    // }

    //var_dump($_POST['cota']);
    if( $_POST['cota'] == '') 
    {
        header("location: areaPrivada.php");
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



    // if (isset($_POST['cota'])) {

    //     $id = $userDao->findByQuota($_POST['cota'])->getId();
    //     $_SESSION['queryId'] = $id;
    //     header("location: areaQuery.php");  
    //     exit;
    // }

    $u = $userDao->findByQuota($_POST['cota']);

    // se não encontrar usuário com o número de cota informado
    if(!$u) {
        header("location: areaPrivada.php");
        exit;
    }

    //$id = $_SESSION['queryId'];
    //echo "ID: ".$id;

    //$u = $userDao->findById($id);
    
    $id = $u->getId();
    $nickname = $u->getNickname();
    $quota = $u->getQuota();
    $name = $u->getName();


    $isAdmin = ((int)$u->getType()) == 1;
    //var_dump($isAdmin);

    
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
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css'); ?>">     
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



    <div class="extrato">
        <h3>Demonstrativo Financeiro</h3>
        <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th class="num">Valor R$</th>
        </tr>
        <?php
        //gera extrato normal na tela e armazena lançamentos futuros 
        $today = strtotime(date('Y/m/d'));
        // echo '$today : '.$today;
        // var_dump($today);
        $futureEntries = [];
        foreach ($statement as $item) {        
            // echo '$item->getDate : '.$item->getDate();
            // var_dump($item->getDate()); 
            if (strtotime($item->getDate()) <= $today) {
                // extrato normal
                $date = date('d/m/Y',strtotime($item->getDate()));
                $description = $item->getDescription();
                $value = $item->getValue();

                if ($value < 0) {
                    echo "<tr><td>".$date."</td><td>".$description."</td><td class='num neg'>".num($value)."</td>";
                } else {
                    echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
                }
                $total = $total + $value;
            } else {
                // armazena lançamentos futuros
                array_push($futureEntries, $item);  
            }      
        }
        echo "</table>";
        if ($value < 0) {
            echo "<span class='num neg'>Saldo atual: ".num($total)."</span>"; 
        } else {
            echo "<span class='num'>Saldo atual: ".num($total)."</span>";
        }
        
        ?>
    </div>


    <div class="futuros">
        <h3>Lançamentos Futuros</h3>
        <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th class="num">Valor R$</th>
        </tr>
        <?php
        //gera extrato de lançamentos futuros na tela 
        $total=0;
        foreach ($futureEntries as $item) {         
            // extrato normal
            $date = date('d/m/Y',strtotime($item->getDate()));
            $description = $item->getDescription();
            $value = $item->getValue();
            if ($value < 0) {
                echo "<tr><td>".$date."</td><td>".$description."</td><td class='num neg_future'>".num($value)."</td>";
            } else {
                echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
            }   
            
            $total = $total + $value;
            // armazena lançamentos futuros
            array_push($futureEntries, $item);           
        }
        echo "</table>";
        if ($value < 0) {
            echo "<span class='num neg_future'>Total lançamentos futuros: ".num($total)."</span>"; 
        } else {
            echo "<span class='num'>Total lançamentos futuros: ".num($total)."</span>";
        }
        ?>
    </div>


<div class ="opcoes">
    <a href="areaPrivada.php"> Voltar</a>  
    <a href="sair.php">Sair </a>
</div>


</div>


</body>
</html>