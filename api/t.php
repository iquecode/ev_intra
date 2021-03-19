<?php
require_once(__DIR__.'/../db/UserDaoMysql.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method === 'post') 
{

    $e = json_decode(file_get_contents('php://input'));
    //$status = $_POST['body'];
    //$e = json_decode($status);
    //$e = $status;
    $userDao = new UserDaoMysql;
    $userDao->addEntry($e->id_user, $e->entry_date, $e->description, $e->value, 
                        $e->id_entry_type, $e->record_user, $e->status, null);
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");
echo json_encode($e);