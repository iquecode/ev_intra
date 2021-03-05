<?php
require_once 'UserDaoMysql.php';
require_once 'classes/Config.php'; 
$pdo = Config::conect();
$userDao = new UserDaoMysql($pdo[1]);




// function addEntryIPCA($id, $correc, $p) {
//     $sql = $p->prepare('INSERT INTO entrys (entry_date, description, value, id_user, id_entry_type) 
//         VALUES (:entry_date, :description, :value, :id_user, :id_entry_type)');
//     $sql->bindValue(':entry_date', '2021-02-28');
//     $sql->bindValue(':description', 'Correção IPCA 1ª Fase - 0.25%');
//     $sql->bindValue(':value', $correc);
//     $sql->bindValue(':id_user', $id);
//     $sql->bindValue(':id_entry_type', 1);
//     $sql->execute();
//     //$sql->debugDumpParams();
// }

function atualiza($id, $p) {
    $sql = $p->prepare('UPDATE entrys SET record_user = :ru, status = :st 
    WHERE id_entry = :id');
    $sql->bindValue(':ru', 32);
    $sql->bindValue(':st', 1);
    $sql->bindValue(':id', $id);
    $sql->execute();
    return true;
 }


for($i = 1; $i <=431; $i++ ){
    atualiza($i, $pdo[1]);
}