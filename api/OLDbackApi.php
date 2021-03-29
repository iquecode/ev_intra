<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');
// require_once(__DIR__.'/../classes/User.php');
// require_once(__DIR__.'/../controls/parts/Statment.php');
// require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/helper.php');

// $method = strtolower($_SERVER['REQUEST_METHOD']);

// if ($method === 'post') 
// {

    // $recebido = json_decode(file_get_contents('php://input'), true);
    
    // $f = $recebido->file;
    // print_r($recebido);

    // $file = $_FILES;
     
    //gravar novo lançamento no banco de dados
    // $e = json_decode(file_get_contents('php://input'));
    // $userDao = new UserDaoMysql;
    // $userDao->addEntry($e->id_user, $e->entry_date, $e->description, $e->value, 
    //                 $e->id_entry_type, $e->record_user, $e->status, null);
         
    //gerar na memoria novo demonstrativo financeiro                        
    // $u = $userDao->findById($e->id_user);
    // $allEntries = $u->getTodayFutureEntries();    
    // $futureEntries = $allEntries['future'];
    // $todayEntries =  $allEntries['today'];
    // $statment = new Statment($todayEntries, $u->getId());  
    // $statmentHTML = $statment->getHTML();          
    
    //*************DEGUG DO RETORNO */
    //  $arquivo = fopen('teste.txt','w');
    //  $texto = $txt;
    //  fwrite($arquivo, json_encode($statmentHTML));
    //  fclose($arquivo);    
     /**************************** */

    //*************DEGUG DO RETORNO */
    //   $arquivo = fopen('teste.txt','w');
    //   //$texto = $txt;
    //   fwrite($arquivo, $recebido);
    //   fclose($arquivo);    
     /**************************** */



// }

// $array = ['a' => 1, 'b' => 2];

// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// header("Content-Type: application/json");


require_once 'LoadStatment.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');


$tmp_name = $_FILES['file']['tmp_name'];

//$data = [$_POST, $_FILES];

// $debug = print_r($_FILES, true);
// file_put_contents('log.txt', $debug);

$handler = new LoadStatment($tmp_name);

$dao = new UserDaoMysql;
$validableEntries = $dao->getAllEntries();

$validables = [];
// $validables['id'] = []; 
// $validables['date'] = [];
// $validables['value'] = [];
// $validables['user_id'] = [];
// $validables['user_quota'] = [];
// $validables['user_info'] = [];
$i = 0;
foreach ($validableEntries as $validable) 
{
    $validables[$i]['id'] =   $validable['entry']->getId(); 
    $validables[$i]['date'] = $validable['entry']->getDate();
    $validables[$i]['value'] = $validable['entry']->getValue(); 
    $validables[$i]['user_id'] = $validable['entry']->getUserId();
    $validables[$i]['user_quota'] = $validable['user_quota'];
    $validables[$i]['user_info'] = $validable['user_info'];  
    $i++;
}


// $debug = print_r($handler->getDeposits(), true);
// file_put_contents('logStat.txt', $debug);

//$data = [$_POST, $handler->getCashFlow()];
$data = ['deposits'           => $handler->getDeposits(), 
         'validable_entries'  => $validables];

$debug = print_r($data, true);
file_put_contents('logStat.txt', $debug);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");
echo json_encode($data);

exit;