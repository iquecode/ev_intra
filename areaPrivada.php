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
    $u = $userDao->findById($id);
    $nickname = $u->getNickname();
    $quota = $u->getQuota();
    $name = $u->getName();

    $params = $userDao->findParams();
    $date_max = date('Y/m/d');
    $date_max = str_replace('/', '-', $date_max);
    
    $date_min = date('Y/m/d', strtotime($params->lastCheck. ' + 1 days'));
    $date_min = str_replace('/', '-', $date_min);

    $isAdmin = ((int)$u->getType()) == 1;
    $statement = $u->getEntrys();
    $total = 0;
    // Ordena os lançamentos por data
    usort($statement, function($a, $b){ return $a->getDate() >= $b->getDate(); });


    //se usuário tiver permissão de administração, percorrrer o db para pegar o número da cota
    // apelido e nome de todos usuários, para colocar no select para consulta no final da página
    $allUsers = $userDao->findAll(); // <-aqui

    

?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Ecovileir@</title>
    <link rel="stylesheet" href="css/style_content.css?v=<?= filemtime('css/style_content.css'); ?>">     
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
        $pendentes = false;
        foreach ($statement as $item) {        
            // echo '$item->getDate : '.$item->getDate();
            // var_dump($item->getDate()); 
            if (strtotime($item->getDate()) <= $today) {
                // extrato normal
                $date = date('d/m/Y',strtotime($item->getDate()));
                $description = $item->getDescription();
                $value = $item->getValue();
                $status = $item->getStatus();
                if ($value < 0) {
                    echo "<tr><td>".$date."</td><td>".$description."</td><td class='num neg'>".num($value)."</td>";
                } else {
                    
                    if ($status == 0) {
                        $pendentes = true;
                        echo "<tr>
                                <td class='pend'>" . $date . "</td>
                                <td class='pend'>" . $description." - a validar"."</td>
                                <td class='num pend'>" . num($value). "</td>";
                    } else {
                        echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
                    }
                }
                $total = $total + $value;
            } else {
                // armazena lançamentos futuros
                array_push($futureEntries, $item);  
            }      
        }
        echo "</table>";


        echo "<div id='rodape_ext'>";

            if ($pendentes) {
                echo "<div id='altera'>";
                echo "<a class='a_small' href='_changes.php'>Alterar / excluir lançamentos a validar.</a>";
                echo "</div>";
            }


            if ($total < 0) {
                echo "<span class='num neg'>Saldo atual: ".num($total)."</span>"; 
            } else {
                echo "<span class='num'>Saldo atual: ".num($total)."</span>";
            }        

        echo "</div>";

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

    <div class = "ecovila">
        <table class="dados_banc">
            <tr>
                <td class="title">Situação financeira da Ecovila</td>
            </tr>
            <tr>
                <td class="content"> Data: <?php echo date('d/m/Y', strtotime($params->lastCheck)) . " - Última conciliação bancária";?></td>
            </tr>
            <tr>
                <td>Saldo em conta corrente</td>
                <td><?php echo num($params->account); ?></td>
            </tr>
            <tr>
                <td>Saldo investido</td>
                <td><?php echo num($params->invest);?></td>
            </tr>
        </table>
    </div>

    <div class ="dados_banc">
        <span class = title>Abaixo, caso deseje, você pode informar depósitos ainda não constantes do seu demonstrativo</span>
        <p class ="atencao">Informe apenas se não constar no demonstrativo</p>
        <p>Envio de comprovante é opcional</p>
        <form id='form_dep' method='POST' enctype="multipart/form-data" action="_upload.php">
            <label>
                Data exata do depósito
                <input class='inp' id="inp_date" type='date' placeholder='Data do depósito' name='data_deposito' required <?php echo " min=" . $date_min . " max=" . $date_max   ?>>
            </label>
            <input class='inp' type='number' onchange='setTwoNumberDecimal' min="0.01" step="0.01" placeholder='Valor exato' name='valor_deposito' required>
            <label for="img">Selecione o comprovante (opcional) - max. 200kb</label>
            <!-- MAX_FILE_SIZE deve preceder o campo input bytes-->
            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
            <input type="file" id="img" name="arquivo" accept="image/*,application/pdf">
            <input class= 'inp' id= 'input_dep_submit'type='submit' value='ENVIAR'>
        </form>

        <p class="title_content">Dados para depósito</p>   
        <span class = "title">Sempre depositive com os centavos da cota</span>
        <table class="banc">
            <tr>
                <td class = "pix" >Ecovila e Santuário Vegano</td>
            </tr>
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

        <div class="banc_mobile">
            <p>Ecovila e Santuário Vegano</p>
            <p>PIX (CNPJ)<span class="banc_mobile_itens">26160053000163</span></p>
            <p>Banco<span class="banc_mobile_itens">104 - Caixa</span></p>
            <p>Agência<span class="banc_mobile_itens">1967</span></p>
            <p>Operação<span class="banc_mobile_itens">003</span></p>
            <p>Conta Corrente<span class="banc_mobile_itens">000654-3</span></p>
            <p>CNPJ<span class="banc_mobile_itens">26.160.053/0001-63</span></p>   
        </div>




    </div>

    <div class ="opcoes">
        <a href="update.php"> Alterar senha e confirmar dados</a>  
        <a href="sair.php">Sair </a>
    </div>

    <?php
    if ($isAdmin) {
        echo "<div class='admin'>";
            echo "<span class='atencao'>Você tem permissão de administração no sistema e pode consultar uma cota individualmente ou então entrar no módulo de administração.</span>";
            
            
            // Form novo
            $optionsHTML = '<option data-default disabled selected>Cota para consulta individual</option>';
            foreach ($allUsers as $u) {
                $q = $u->getQuota();
                $nn = $u->getNickName();
                $n = $u->getName();
                $optionsHTML = $optionsHTML . "<option value={$q}>{$q} - {$nn} - {$n}</option>";
                //echo "{$q} - {$nn} - {$n}<br/>";
                //print_r($u->getQuota());
            }
            $form = "<form id='form_admin' method='POST' action='areaQuery.php'>";
            $outputHTML = "<select class='inp' id='input_admin' name='cota'>{$optionsHTML}</select>";
            $submit = "<input class= 'inp' id='input_admin_sub' type='submit' value='CONSULTA INDIVIDUAL'>";
            $form= $form . $outputHTML . $submit . "</form>"; 
            echo $form; 
            // fim Form novo       
            // echo "<form id='form_admin' method='POST' action='areaQuery.php'>";
            //     echo "<input class='inp' id='input_admin' type='number' placeholder='nº da cota->consulta individual' name='cota'>";
            //     echo "<input class= 'inp' id= 'input_admin_sub'type='submit' value='CONSULTA INDIVIDUAL'>";
            // echo "</form>";

            echo "<form id='form_mod_admin' method='POST' action='_admin.php'>";
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

    <script src="js/scriptAreaPrivativa.js"></script>    
</body>
</html>