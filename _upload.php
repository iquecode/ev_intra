<?php
    session_start();
    if(!isset($_SESSION['userId'])) {
        header("location: index.php");
        exit;
    }

function dateValidate($date) {
    //cria um array
    $array = explode('-', $date);
    //garante que o array possue tres elementos (dia, mes e ano)
    if(count($array) == 3){
        $y = (int)$array[0];
        $m = (int)$array[1];
        $d = (int)$array[2];
        //testa se a data é válida
        if(checkdate($m, $d, $y)){
            return 1;  //data válida
        }else{
            return 0; // data inválida;
        }
    }else{
        return -1; // formato inválido
    }
}
?> 


<?php
 require_once 'db/UserDaoMysql.php';
 //require_once 'classes/Config.php'; 

/******
 * Upload do arquivo do comprovante de pgmto
 ******/
$destino = NULL;
// verifica se foi enviado um arquivo
if ( isset( $_FILES[ 'arquivo' ][ 'name' ] ) && $_FILES[ 'arquivo' ][ 'error' ] == 0 ) {
    //echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo' ][ 'name' ] . '</strong><br />';
    //echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo' ][ 'type' ] . ' </strong ><br />';
    //echo 'Temporáriamente foi salvo em: <strong>' . $_FILES[ 'arquivo' ][ 'tmp_name' ] . '</strong><br />';
    //echo 'Seu tamanho é: <strong>' . $_FILES[ 'arquivo' ][ 'size' ] . '</strong> Bytes<br /><br />';
 
    $arquivo_tmp = $_FILES[ 'arquivo' ][ 'tmp_name' ];
    $nome = $_FILES[ 'arquivo' ][ 'name' ];
 
    // Pega a extensão
    $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
 
    // Converte a extensão para minúsculo
    $extensao = strtolower ( $extensao );
 
    // Somente imagens ou pdf, .jpg;.jpeg;.gif;.png;.pdf
    // Aqui eu enfileiro as extensões permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if ( strstr ( '.jpg;.jpeg;.gif;.png;.pdf', $extensao ) ) {

        //Verifica se o arquivo está dentro do limite máximo - 200kb
        if ( $_FILES[ 'arquivo' ][ 'size' ] <=200000 ) {

            // Cria um nome único - id_data_timestamp
            // Evita que duplique as imagens no servidor.
            // Evita nomes com acentos, espaços e caracteres não alfanuméricos
            //$novoNome = uniqid ( time () ) . '.' . $extensao;
            $novoNome =  $_SESSION['userId'] . '_' . str_replace('-', '', $_POST['data_deposito'])  . '_' . time() . '.' . $extensao;
    
            // Concatena a pasta com o nome
            $destino = 'upload/receipt/' . $novoNome;
    
            // tenta mover o arquivo para o destino
            if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
                //echo 'Comrpovante salvo com sucesso em : <strong>' . $destino . '</strong><br />';
                echo 'Comprovante salvo com sucesso<br/>';
                //echo ' < img src = "' . $destino . '" />';
            }
            else 
                echo 'Erro ao salvar comprovante de depósito, permissão de escrita negada pelo servidor.<br />';
        }
        else
            echo 'Comprovante de depósito não salvo pois está fora do limite permitido - máximo 200kb.<br />';  
    }
    else
        echo 'Comprovante de depósito não salvo. Permitido apenas arquivos "*.jpg;*.jpeg;*.gif;*.png;*.pdf"<br />';
}
else
    //echo 'Comprovante de depósito não salvo. Erro no envio e/ou arquivo fora do limite permitido - máx. 200kb.<br />';
    //echo 'sem comprovante de depósito';
    echo '';



//$value = (filter_input(INPUT_POST, 'valor_deposito', FILTER_SANITIZE_NUMBER_FLOAT))/100;

$value = $_POST['valor_deposito'];
//acessar banco de dados
//$pdo = Config::conect();
//$userDao = new UserDaoMysql($pdo[1]);
$userDao = new UserDaoMysql();
$params = $userDao->findParams();
$date_max = date('Y/m/d');
$date_max = str_replace('/', '-', $date_max);
$date_min = date('Y/m/d', strtotime($params->lastCheck. ' + 1 days'));
$date_min = str_replace('/', '-', $date_min);

$error = false;
$dateValid = dateValidate($_POST['data_deposito']);
if ( !($dateValid && $_POST['data_deposito'] >= $date_min && $_POST['data_deposito'] <= $date_max) )  {
    $error = true;
}


if ($error) {
    echo "<br/> Erro: Lançamento não gravado no banco de dados. A data " . $_POST['data_deposito'] .
    "não é válida para a aplicaçao. Formato deve ser yyyy-mm-dd e estar entre " .
    $date_min . " e " . $date_max;
}

if ( !(is_numeric($value) && $value >= 0.01) ) {
    $error = true;
    echo "<br/> Erro: Lançamento não gravado no banco de dados. O valor informado " . $_POST['valor_deposito'] .
    "não é válido."; 
}   

// se passou pelas validações nos campos de data e valor, gravar no DB
if ($error == false) {
    $value = round($value, 2); //arrendodar para 2 casas decimais por questão de segurança, para garantir integridade do db
     //gravar no banco de dados
    $e = $userDao->addEntry($_SESSION['userId'], $_POST['data_deposito'], 'depósito / amortização', $value, 4, $_SESSION['userId'], 0, $destino);
    //echo "ID entry: " . $e->getId(); 
    //echo "<br/> OK -> GRAVAR NO DB!";
    echo "Informação de depósito salva com sucesso : )! <br/><br/>";
    echo "<a href='areaPrivada.php'>Clique aqui para voltar e conferir seu demonstrativo!</a>";
}


?>
