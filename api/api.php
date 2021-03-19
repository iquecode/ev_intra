<?php
require_once(__DIR__.'/../db/UserDaoMysql.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);



$status = 0;
if ($method === 'post') 
{



//$status = $_POST['body'];
$status = json_decode(file_get_contents('php://input'), true);
//$status = $_POST;
//$status = $_REQUEST;


$txt = '';
foreach ($status as $item) {
    $txt .= $item;
}

$arquivo = fopen('post.txt','w');
$texto = $txt;
fwrite($arquivo, $texto);
fclose($arquivo);    


// $e = json_decode($status);

// $userDao = new UserDaoMysql;
// $userDao->addEntry($e->id_user, $e->entry_date, $e->description, $e->value, 
//                      $e->id_entry_type, $e->record_user, $e->status, null);

// $txt = '';
// $i=1;
// foreach ($array as $key => $value) {
//      $txt .= $key . ' => '. $value;
//      $i++;
// }


// $arquivo = fopen('post.txt','w');
// $texto = $txt;
// fwrite($arquivo, $texto);
// fclose($arquivo);




}





header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");
echo $status;