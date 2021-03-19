<?php
require_once __DIR__.'/../db/UserDaoMysql.php';

$method = strtolower($_SERVER['REQUEST_METHOD']);

if($method === 'post') {

    // $id_user = filter_input(INPUT_POST, 'id_user');
    // $entry_date = filter_input(INPUT_POST, 'entry_date');
    //$description = filter_input(INPUT_POST, 'description');
    // $value = filter_input(INPUT_POST, 'value');
    // $id_entry_type = filter_input(INPUT_POST, 'id_entry_type');
    // $record_user = filter_input(INPUT_POST, 'record_user');
    // $status = filter_input(INPUT_POST, 'status');

    //print_r($description);

    $userDao = new $UserDaoMysql;
    $userDao->addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, $status, null);

    // $array['result'] = [
    //        'status' => true
    // ];

    $array = [];
    

    // if($title && $body) {

        // $sql = $pdo->prepare("INSERT INTO notes (title, body) VALUES (:title, :body)");
        // $sql->bindValue(':title', $title);
        // $sql->bindValue(':body', $body);
        // $sql->execute();

        // $id = $pdo->lastInsertId();

        // $userDao = new $UserDaoMysql;
        // $userDao->addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, $status, null);

        // $array['result'] = [
        //     'status' => true
        // ];

    // } else {
    //     $array['error'] = 'Campos não enviados';
    // }

} else {
    $array['error'] = 'Método não permitido (apenas POST)';
}

require('return.php');