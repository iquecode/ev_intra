<?php
require_once 'LoadStatment.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');
$tmp_name = $_FILES['file']['tmp_name'];

$handler = new LoadStatment($tmp_name);
$dao = new UserDaoMysql;
$validableEntries = $dao->getAllEntries('all');
$validables = [];
$i = 0;
foreach ($validableEntries as $validable) 
{
    $validables[$i]['id'] =   $validable['entry']->getId(); 
    $validables[$i]['date'] = $validable['entry']->getDate();
    $validables[$i]['value'] = $validable['entry']->getValue(); 
    $validables[$i]['user_id'] = $validable['entry']->getUserId();
    $validables[$i]['status'] = $validable['entry']->getStatus();
    $validables[$i]['user_quota'] = $validable['user_quota'];
    $validables[$i]['user_info'] = $validable['user_info'];  
    $i++;
}

$cF = $handler->getCashFlow();
$data = ['deposits'           => $handler->getDeposits(), 
         'validable_entries'  => $validables,
         'cf_array'           => $cF['array'],
         'cf_file'            => $cF['file']
        ];

//$debug = print_r($data, true);
//file_put_contents('logStat.txt', $debug);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");
echo json_encode($data);
exit;
