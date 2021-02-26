<?php
require_once 'UserDaoMysql.php';
require_once 'classes/Config.php'; 
//$userDao = new UserDaoMysql($pdo);
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



<div class="frame">



        <div class ="app">
           
            <h1 class="title">Intranet Ecovila Vegana</h1>
            
            <!-- <form method="POST" action="processa.php"> -->
            <form class="form" method="POST">
                
                <input id='input1' type="email" placeholder="Usuário" name="email">
                <input type="password" placeholder="Senha" name="senha">
                <input type="submit" value="ENTRAR">
                <!-- <a href="cadastrar.php">Ainda não é inscrit@?<strong>Cadastre-se!</a> -->
            
            
            </form>
            <div class="base">
                <span>Versão beta 0.01 - 2021</span>
                <span>github.com/iquecode/ev_intra</span>
            </div>
            
        </div>



    <?php
    if (isset($_POST['email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
        
        //verificar se esta preenchido
        if( !empty($email) && !empty($senha) ) {
            
            $pdo = Config::conect();
            if($pdo[0] == true) {
                $userDao = new UserDaoMysql($pdo[1]);
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
                        <?php echo "Erro: ".$u->msgErro;?>
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

    
</body>
</html>