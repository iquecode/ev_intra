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
    <link rel="stylesheet" href="css/style_content.css?v=<?= filemtime('css/style.css'); ?>">     
</head>
<body>


    <div class="layout">


    <div class = "cabecalho">
        <?php
        echo "Seja bem vind@ ".$nickname;
        echo "<br/>";
        echo "---------------------------------------------------------";
        echo "<br/>";
        echo "Cota ".$quota." - ".$name;
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


    <div class ="dados_banc">

        <!-- <p>No início de cada mês a tesouraria gera extrato da conta da Ecovila do mês anterior e o sistema faz a conferência dos depósitos com base no valor dos centavos.<p>
        <p>Tais valores, depois de validados, são lançados no demonstrativo individual de cada ecovileir@, de forma automática.</p>
        <p>Mas você também pode informar depósito que ainda não conste no seu demostrativo.</p>
        <p>Neste caso, a referida informação será lançada de forma imediata, mas com a a pendência de validação por parte da tesouraria.</p> -->
        <span class = title>Abaixo, caso deseje, você pode informar depósitos ainda não constantes do seu demonstrativo</span>
        <p class ="atencao">Informe apenas se não constar no demonstrativo</p>
        <p>Envio de comprovante é opcional</p>
        <form id='form_dep' method='POST' enctype="multipart/form-data" action="_upload.php">
            <label>
                Data exata do depósito
                <input class='inp' id="inp_date" type='date' placeholder='Data do depósito' name='data_deposito'>
            </label>
            <input class='inp' type='number' min="0.01" step="0.01" placeholder='Valor exato' name='valor_deposito'>
            <label for="img">Selecione o comprovante (opcional) - max. 200kb</label>
            <!-- MAX_FILE_SIZE deve preceder o campo input bytes-->
            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
            <input type="file" id="img" name="arquivo" accept="image/*,application/pdf">
            <input class= 'inp' id= 'input_dep_submit'type='submit' value='ENVIAR'>
        </form>

        <!-- <form id='form_dep' method='POST'>
            <label>
                Data exata do depósito
                <input class='inp' id="inp_date" type='date' placeholder='Data do depósito' name='data_deposito'>
            </label>
            <input class='inp' type='number' placeholder='Valor exato' name='valor_deposito'>
            <label for="img">Selecione o comprovante (opcional)</label> <input type="file" id="img" name="img" accept="image/*,application/pdf">
            <input class= 'inp' id= 'input_dep_submit'type='submit' value='ENVIAR'>
        </form> -->

        <p class="title_content">Dados para depósito</p>   
        <span class = "title">Sempre depositive com os centavos da cota</span>
        <table class="banc">
            <tr>
                <td class = "pix" >PIX (CNPJ)</td>
                <td class = "pix" >26160053000163</td>
            </tr>
            <tr>
                <td class = "content">Caixa Econômica Federal</td>
                <td class = "content bold">Banco 104</td>
            </tr>
            <tr>
                <td class = "content">Agência</td>
                <td class = "content bold">1967</td>
            </tr>
            <tr>
                <td class = "content">Operação</td>
                <td class = "content bold">003</td>
            </tr>
            <tr>
                <td class = "content">Conta Corrente</td>
                <td class = "content bold">000654-3</td>
            </tr>
            <tr>
                <td class = "content">CNPJ</td>
                <td class = "content bold">26.160.053/0001-63</td>
            </tr>
        </table>

        
    
    </div>


    <div class ="opcoes">
        <a href="update.php"> Alterar senha e confirmar dados</a>  
        <a href="sair.php">Sair </a>
    </div>


    <?php
    if ($isAdmin) {
        echo "<div class='admin'>";
            echo "<span class='atencao'>Você tem permissão de administração no sistema e pode consultar uma cota individualmente ou então entrar no módulo de administração.</span>";
            echo "<form id='form_admin' method='POST' action='areaQuery.php'>";
                echo "<input class='inp' id='input_admin' type='number' placeholder='nº da cota->consulta individual' name='cota'>";
                echo "<input class= 'inp' id= 'input_admin_sub'type='submit' value='CONSULTA INDIVIDUAL'>";
            echo "</form>";

            echo "<form id='form_mod_admin' method='POST'>";
                echo "<input class= 'inp' id= 'input_admin_mod'type='submit' value='MÓDULO DE ADMINISTRAÇÃO'>";
            echo "</form>";


        echo "</div>";

        echo"</div>";  
        //verificar se clicou no botao
        if (isset($_POST['cota'])) {

            $id = $userDao->findByQuota($_POST['cota'])->getId();
            $_SESSION['queryId'] = $id;
            header("location: areaQuery.php");  
            exit;
        }
        echo"</div>";


    } 

    ?>







    

    </div>


</body>
</html>