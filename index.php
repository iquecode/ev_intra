<?php
require_once 'db/UserDaoMysql.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=0">
    <title>Área Ecovileir@</title>
    <!-- <link rel="stylesheet" href="css/style.css">       -->
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css'); ?>">

</head>

<body>
    <div class="layout"> 

        <header class="header">
                    <a class="logo" href=""><img class="logo" src="images/logo ev_vegana.png" alt="Logo"/></a>  
                    <p class="title_header">Intranet</p>
        </header> 

        <div class ="app">
            <!-- <form method="POST" action="processa.php"> -->
            <form class="form" method="POST">
                <input id='input1' type="email" placeholder="Usuário" name="email">
                <input type="password" placeholder="Senha" name="senha">
                <input type="submit" value="ENTRAR">
                <!-- <a href="cadastrar.php">Ainda não é inscrit@?<strong>Cadastre-se!</a> -->
            </form>
            <div class="base">
                <span class="base_info">2021 - Versão beta 1.02</span>
                <a class="base_info" href="https://github.com/iquecode/ev_intra" target="_blank">
                    github.com/iquecode/ev_intra
                </a>
            </div>  
            <?php
            if (isset($_POST['email'])) {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
                //verificar se esta preenchido
                if( !empty($email) && !empty($senha) ) {
                    $userDao = new UserDaoMysql();
                    if($userDao->login($email,$senha)){
                        header("location: areaPrivada.php");
                    } else {
                        ?>
                        <div class="msg-erro">
                            Email e/ou senha estão incorretos!
                        </div>
                        <?php               
                    }
                } else {
                    ?>
                    <div class="msg-erro">
                        Preencha todos os campos!
                    </div>
                    <?php      
                }
            }
            ?>
        </div>

        <!-- <footer class="footer">
                
        </footer> -->

    </div>

</body>
</html>