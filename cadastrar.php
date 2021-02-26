<?php
    require_once 'UserDaoMysql.php';
    require_once 'classes/Config.php'; 
    
    
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Ecovileir@</title>
    <link rel="stylesheet" href="css/estilo.css">      
</head>
<body>
    <div id="corpo-form">
        <!-- <form method="POST" action="processa.php"> -->
        <form method="POST">
            <h1>Cadastrar</h1>
            <input type="text" name="quota" placeholder="Cota" maxlength="3">
            <input type="text" name="name" placeholder="Nome Completo" maxlength="60">
            <input type="text" name="nickname" placeholder="Como ser chamad@" maxlength="20">
            <input type="email" name="email" placeholder="Usuário" maxlength="40">
            <input type="password" name="pass" placeholder="Senha" maxlength="15">
            <input type="password" name="pass2" placeholder="Confirmar Senha" maxlength="15">
            <input type="submit" value="Cadastrar">
        </form>
    </div>
    <?php
    //verificar se clicou no botao

    if (isset($_POST['email'])) {

        $quota = filter_input(INPUT_POST, 'quota', FILTER_SANITIZE_NUMBER_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
        $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING);
        
        //verificar se esta preenchido
        if(!empty($quota) && !empty($name) && !empty($nickname) && !empty($email) && !empty($pass) && 
        !empty($pass2)) {
            
            $pdo = Config::conect();
            
            if($pdo[0] == true) {

                $userDao = new UserDaoMysql($pdo[1]);

                if($pass == $pass2) {

                   if( !$userDao->findByEmail($email) ) {
                        $u = new User();  
                        $u->setQuota($quota);
                        $u->setName($name);
                        $u->setNickname($nickname);
                        $u->setEmail($email);
                        $u->setPass( md5($pass) );
                        $userDao->add( $u );
                        ?>
                        <div id="msg-sucesso">
                            Cadastrado com sucesso! Acesse para entrar! 
                        </div>
                        <?php
                   
                    } else {
                        

                        ?>
                        <div class="msg-erro">
                            Email já cadastrado!! 
                        </div>
                        <?php
                   }
                   
                      
                } else {
                    ?>
                    <div class="msg-erro">
                        Senha e Confirmar senha não correspondem!
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="msg-erro">
                    <?php echo "Erro: ".$pdo[1];?> // erro conexão db
                </div>
                <?php
                
            }

            

            }
        else {

            ?>
            <div class="msg-erro">
                Preencha todos os campos!
            </div>
            <?php

                
        } 

            

    }


    
    
    

    ?>
    
</body>
</html>