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
    $pdo = Config::conect();
    $userDao = new UserDaoMysql($pdo[1]);
    $id = $_SESSION['userId'];
    $u = $userDao->findById($id);
    $quota = $u->getQuota();
    $nickname = $u->getNickName();
    $name = $u->getName();
    $email = $u->getEmail();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração bem sucedida</title>

    <h1>Alteração bem sucedida!</h1>

    <p>Cota: <?php echo $quota?></p>
    <p>Nome: <?php echo $name?></p>
    <p>Apelido: <?php echo $nickname?></p>
    <p>Usuário de acesso: <?php echo $email?></p>

    <br>
    <br>

    <a href="sair.php">Clique aqui para sair e acessar novamente com os novos dados.</a> 
    


</head>
<body>
    
</body>
</html>