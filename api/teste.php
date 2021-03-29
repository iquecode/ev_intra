<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');


$dao = new UserDaoMysql;
$validableEntries = $dao->getAllValidableEntries();

$data = $validableEntries;

// $debug = print_r($data, true);
// file_put_contents('logStat.txt', $debug);

echo "<pre>";
print_r($data);