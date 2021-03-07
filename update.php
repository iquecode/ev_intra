<?php
    session_start();
    if(!isset($_SESSION['userId'])) {
        header("location: index.php");
        exit;
    }

    require_once 'UserDaoMysql.php';
    require_once 'classes/Config.php'; 
    require_once 'helper.php';
    $pdo = Config::conect();
    $userDao = new UserDaoMysql($pdo[1]);
    $id = $_SESSION['userId'];
    $u = $userDao->findById($id);
    $quota = $u->getQuota();
    $nickname = $u->getNickName();
    $name = $u->getName();
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

        <div class ="app">
            <div class="info">
                <?php echo "<h2>".$nickname."  -  Cota: ".$quota."  -  ".$name."</h2>" ?>
            </div>
            <div class="title">
                <h1 >Alterar senha e confirmar dados</h1>
            </div>
            <form class="form" method="POST">
                <label >
                    Como gostaria de ser chamad@?
                    <input class="label" type="text" name="nickname" value="<?php echo $u->getNickname()?>" maxlength="20">
                </label>  
                <label >
                    Email válido para entrar no sistema
                    <input class="label" type="email" name="email" value="<?php echo $u->getEmail()?>" maxlength="40">
                </label>
                <input type="password" name="pass" placeholder="Nova Senha" maxlength="15">
                <input type="password" name="pass2" placeholder="Confirm. Senha" maxlength="15">
                <input type="submit" value="Atualizar">
            </form>
            <div class="base">
                <span class="base_info">2021 - Versão beta 1.02</span>
                <a class="base_info" href="https://github.com/iquecode/ev_intra" target="_blank">
                    github.com/iquecode/ev_intra
                </a>
            </div>  
            <?php
            //verificar se clicou no botao
            if (isset($_POST['pass'])) {
                // $quota = filter_input(INPUT_POST, 'quota', FILTER_SANITIZE_NUMBER_INT);
                // $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
                $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING);
                //verificar se esta preenchido
                if(!empty($nickname) && !empty($email) && !empty($pass) && 
                !empty($pass2)) {
                    $pdo = Config::conect();
                    if($pdo[0] == true) {
                        $userDao = new UserDaoMysql($pdo[1]);
                        if($pass == $pass2) {
                        $u2 = $userDao->findByEmail($email);  
                        if( !$u2 || ($u2->getId() == $u->getId()) ) {
                                $u->setNickname($nickname);
                                $u->setEmail($email);
                                $u->setPass( md5($pass) );
                                $userDao->update( $u );
                                // header("Refresh: 0");
                                ?>
                                <div id="msg-sucesso">
                                    Alteração bem sucedida! 
                                    <?php 
                                        header("location: sucess.php");
                                        exit;
                                    ?>
                                </div>
                                <?php
                            } else  {
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
        </div>
        <div class="menu">
            <a href="areaPrivada.php"> Voltar |</a>
            <a href="sair.php">| Sair </a>         
        </div>
            
    </div>
</body>
</html>