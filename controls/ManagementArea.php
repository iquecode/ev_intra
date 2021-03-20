<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'controls/Layout.php';
require_once 'controls/StatmentsArea.php';
require_once 'controls/parts/Msg.php';


class ManagementArea
{
    private $html;
    private $allUsers;
    private $entryTypes;
    
    public function __construct()
    {
        $userDao = new UserDaoMysql();
        $id = $_SESSION['userId'];
        $u = $userDao->findById($id);
        $nickname = $u->getNickname();
        $quota = $u->getQuota();
        $name = $u->getName();
        $params = $userDao->findParams();
        $date_max = date('Y/m/d');
        $date_max = str_replace('/', '-', $date_max);
        $date_min = date('Y/m/d', strtotime($params->lastCheck. ' + 1 days'));
        $date_min = str_replace('/', '-', $date_min);
        $isAdmin = ((int)$u->getType()) == 1;
        $this->allUsers = $userDao->findAllWithEntries();
        $this->entryTypes = $userDao -> findEntryTypes();
    }

    public function load()
    {
        $title = 'Área de Administração';
        $css ='css/style_management.css';
        $js ='js/jsManagementArea.js';
        $statmentsArea = new StatmentsArea($this->allUsers, $this->entryTypes);
        $content = $statmentsArea->getHTML();
        $this->html = new Layout($title, $css, $js, $content, 2);
    }

    public function show()
    {
        $this->load();
        $this->html->show();
    }

    
    
    
    //$entry_date, $id_entry_type, $description, $value  -> passados na requisição 
    //$record_user, $id_user,

    //falta:  
    //$status
    //$img

    public function saveEntryUser($params)
    {
        // echo "AQUI É O savesaveEntryUser() da Classe ManagementArea!";    
        // echo "<pre>";
        // print_r($params); 
        // echo "<br><br>";
        // print_r($_SESSION['userId']); //recor user.
        
       

        extract($params);
        //print_r($description);
        $record_user = $_SESSION['userId'];
        $status = 1;
        $img=null;
        $userDao = new UserDaoMysql();
        $userDao->addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, 
            $status, $img);

        header ('location: index.php?class=ManagementArea');
        exit;
        // return true;
    }

}