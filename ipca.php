<?php
require_once 'UserDaoMysql.php';
require_once 'classes/Config.php'; 
$pdo = Config::conect();
$userDao = new UserDaoMysql($pdo[1]);




function addEntryIPCA($id, $correc, $p) {
    $sql = $p->prepare('INSERT INTO entrys (entry_date, description, value, id_user, id_entry_type) 
        VALUES (:entry_date, :description, :value, :id_user, :id_entry_type)');
    $sql->bindValue(':entry_date', '2021-02-28');
    $sql->bindValue(':description', 'Correção IPCA 1ª Fase - 0.25%');
    $sql->bindValue(':value', $correc);
    $sql->bindValue(':id_user', $id);
    $sql->bindValue(':id_entry_type', 1);
    $sql->execute();
    //$sql->debugDumpParams();
}



//ipca = 0.25%

for($i = 1; $i <=33; $i++ ){

    $u = $userDao->findById($i);
    $statement = $u->getEntrys();
    // var_dump($statement);
    $total = 0;
    foreach ($statement as $item) {
        $value = $item->getDescription();
        if($value == 'saldo devedor 1ª fase' || $value == 'depósito/amortização') {
             $total = $total + $item->getValue();
        }
    //     if($value == 'depósito/amortização') {
    //         $total = $total - $item->getValue();
    //    }
    }


    $ipcaCorrec = 0;
    if ($total < 0) {
        $ipcaCorrec = round($total * 0.0025,2);  
    }


    if ($ipcaCorrec<0) {
        echo "Cota: ".$u->getQuota()."| ".$u->getName()."| Total: ".$total."| Correção IPCA: ".$ipcaCorrec."<br>";

        addEntryIPCA($i, $ipcaCorrec, $pdo[1]);

    }

   

}




