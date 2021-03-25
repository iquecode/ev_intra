<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'controls/Layout.php';
require_once 'controls/StatmentsArea.php';
require_once 'controls/parts/Msg.php';
require_once 'controls/parts/ListValidate.php';


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


    private function getValidableEntries()
    {
        $validableEntries = [];
        foreach ($this->allUsers as $u) 
        {
            foreach ($u->getEntries() as $entry)
            {
                $validable = $entry->getStatus() == 0 ? true : false;
                if ($validable)  
                {
                    $userInfo = $u->getQuota() . ' - ' . $u->getNickName() . ' - ' . $u->getName();
                    //$validableEntries[] = array ('entry'=>$entry, 'user_info'=>$userInfo); 
                    $validableEntries[] = ['entry'=>$entry, 'user_info'=>$userInfo];
                }

            }   
        }
        return $validableEntries; 
    }



    public function load()
    {
        $title = 'Área de Administração';
        $css ='css/style_management.css';
        $js ='js/jsManagementArea.js';
        $statmentsArea = new StatmentsArea($this->allUsers, $this->entryTypes);
        $listValidate = new ListValidate($this->getValidableEntries());

        $listChange = new ListValidate($this->getValidableEntries(), 2);   

        $content = $statmentsArea->getHTML() . $listValidate->getHTML() . $listChange->getHTML();

        //$content = $statmentsArea->getHTML() . $listValidate->getHTML();

        $header = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/html/management_area/header/header.html');
        $this->html = new Layout($title, $css, $js, $content, 2, $header);
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


    public function updateValidateList()
    {
        echo "UPDATEVaLIDATElIST!";
        echo "<pre>";
        print_r($_POST);

        $userDao = new UserDaoMysql();

        $params = $_POST;
        $idsEntries = [];
        foreach ($params as $param => $value)
        {
            if (substr($param, 0, 5) == 'check' && $param != 'check_all')
            {
                $idsEntries[] = intval(substr($param, 5));
            }  
        }

        
        $action = isset($params['delete'])   ? 'delete'   : ''; 
        $action = isset($params['change'])   ? 'change'   : $action;
        $action = isset($params['validate']) ? 'validade' : $action; 


        foreach ($idsEntries as $idEntry) 
        {
            switch ($action) 
            {
                case 'validade':
                    $userDao->validateEntry($idEntry);   
                    break;
                case 'conf_change':
                    $userDao->changeValidableEntry($idEntry);
                    break;
                case 'delete':
                    $userDao->deleteValidableEntry($idEntry);   
                    break;
                case 'change':
                    $listChange = new ListValidate($this->getValidableEntries(), 2);   
                    break;
            }
        }   

        // if (isset($params['validate'])) 
        // {
        //     echo "VALIDATE SETADO!<br><br>";
        //     print_r($idsEntries);
        //     foreach ($idsEntries as $idEntry) 
        //     {
        //         $userDao->validateEntry($idEntry);
        //     }   
        // } 
        

    }

    


}