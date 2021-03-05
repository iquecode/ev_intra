<?php
    session_start();
    if(!isset($_SESSION['userId'])) {
        header("location: index.php");
        exit;
    }
?> 



<?php

// $destino = 'upload/receipt/' . $_FILES['arquivo']['name'];
 
// $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
 
// move_uploaded_file( $arquivo_tmp, $destino  );


/******
 * Upload de imagens
 ******/
 
// verifica se foi enviado um arquivo
if ( isset( $_FILES[ 'arquivo' ][ 'name' ] ) && $_FILES[ 'arquivo' ][ 'error' ] == 0 ) {
    echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo' ][ 'name' ] . '</strong><br />';
    echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo' ][ 'type' ] . ' </strong ><br />';
    echo 'Temporáriamente foi salvo em: <strong>' . $_FILES[ 'arquivo' ][ 'tmp_name' ] . '</strong><br />';
    echo 'Seu tamanho é: <strong>' . $_FILES[ 'arquivo' ][ 'size' ] . '</strong> Bytes<br /><br />';
 
    $arquivo_tmp = $_FILES[ 'arquivo' ][ 'tmp_name' ];
    $nome = $_FILES[ 'arquivo' ][ 'name' ];
 
    // Pega a extensão
    $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );
 
    // Converte a extensão para minúsculo
    $extensao = strtolower ( $extensao );
 
    // Somente imagens, .jpg;.jpeg;.gif;.png
    // Aqui eu enfileiro as extensões permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if ( strstr ( '.jpg;.jpeg;.gif;.png;.pdf', $extensao ) ) {


        //Verifica se o arquivo está dentro do limite máximo
        if ( $_FILES[ 'arquivo' ][ 'size' ] <=200000 ) {

            // Cria um nome único para esta imagem
            // Evita que duplique as imagens no servidor.
            // Evita nomes com acentos, espaços e caracteres não alfanuméricos
            //$novoNome = uniqid ( time () ) . '.' . $extensao;
            $novoNome =  $_SESSION['userId'] . '_' . str_replace('-', '', $_POST['data_deposito'])  . '_' . time() . '.' . $extensao;
    
            // Concatena a pasta com o nome
            $destino = 'upload/receipt/' . $novoNome;
    
            // tenta mover o arquivo para o destino
            if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
                echo 'Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br />';
                echo ' < img src = "' . $destino . '" />';
            }
            else
                echo 'Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />';

        }
        else
            echo 'Comprovante de depósito não salvo pois está fora do limite permitido - máximo 200kb.<br />';  
    }
    else
        echo 'Comprovante de depósito não salvo. Permitido apenas arquivos "*.jpg;*.jpeg;*.gif;*.png;*.pdf"<br />';
}
else
    echo 'Comprovante de depósito não salvo. Erro no envio e/ou arquivo fora do limite permitido - máx. 200kb.<br />';



//var_dump($_POST);
echo "<br/>";
echo "Data: " . $_POST['data_deposito'] . "<br />";
echo "Valor: " . $_POST['valor_deposito'] . "<br />";
echo "Comprovante: ". $destino;
echo "ID_User:" . $_SESSION['userId'];

?>

<!-- 

<img class="logo" src= <?php echo '"' . $destino . '"'   ?> alt="Logo"/> -->