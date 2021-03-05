<?php
    session_start();
    if(!isset($_SESSION['userId'])) {
        header("location: index.php");
        exit;
    }

    echo "Poxa... essa parte ainda não foi implementada : /. Mas até fim de março deve estar pronta ; )! <br/><br/>";

    echo "<a href='areaPrivada.php'>Clique aqui para voltar!</a>";

?> 