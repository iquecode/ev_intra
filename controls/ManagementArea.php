<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'controls/Layout.php';
require_once 'controls/StatmentsArea.php';
require_once 'controls/parts/Msg.php';
require_once 'controls/parts/ListValidate.php';
require_once 'controls/parts/Opt.php';
require_once 'controls/parts/BatchEntries.php';

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
        $css2 ='css/style_bank.css';
        $js ='js/jsManagementArea.js';
        $statmentsArea = new StatmentsArea($this->allUsers, $this->entryTypes);
        $listValidate = new ListValidate($this->getValidableEntries());
        $listChange = new ListValidate($this->getValidableEntries(), 2);  

        $batchEntries = new BatchEntries($this->allUsers, $this->entryTypes);

        $templateLoadBankSt =  $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . "html/management_area/bank_statment/bank_statment.html"; 
        $loadBankSt = file_get_contents($templateLoadBankSt);
        $content = $statmentsArea->getHTML() . $listValidate->getHTML() . $listChange->getHTML() . $loadBankSt . $batchEntries->getHTML();
        $header = file_get_contents($_SERVER['DOCUMENT_ROOT'] . DIR_BASE . 'html/management_area/header/header.html');
        $this->html = new Layout($title, $css, $js, $content, 3, $header, '', $css2);
    }

    public function show()
    {
        $this->load();
        $this->html->show();
    }


    public function saveEntryUser($params)
    {
        extract($params);
        $record_user = $_SESSION['userId'];
        $img=null;
        $userDao = new UserDaoMysql();

        $entryTypes = $userDao->findEntryTypes();
        $sign = 1;
        foreach ($entryTypes as $entryType)
        {
            if ($entryType->getId() == $id_entry_type ) 
            {
                $sign = $entryType->getSign();
            }
        }

        if ( ($value >= 0 && $sign < 0) || ( ($value < 0 && $sign > 0) ) )
        {
            $value = $value * -1;
        }
       
        $userDao->addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, 
            $status, $img);

        header ('location: index.php?class=ManagementArea');
        exit;
    }


    public function updateValidateList()
    {
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
        $action = isset($params['conf_change']) ? 'conf_change' : $action; 

        foreach ($idsEntries as $idEntry) 
        {
            switch ($action) 
            {
                case 'validade':
                    $userDao->validateEntry($idEntry);   
                    break;
                case 'conf_change':
                    $dateEntry =  $params["data_deposito{$idEntry}"];
                    $valueEntry = $params["valor_deposito{$idEntry}"];
                    $userDao->changeValidableEntry($idEntry, $dateEntry, $valueEntry);
                    break;
                case 'delete':
                    $userDao->deleteValidableEntry($idEntry);   
                    break;
                case 'change':
                    $listChange = new ListValidate($this->getValidableEntries(), 2);   
                    break;
            }
        }   

        header('location: index.php?class=ManagementArea');
        exit;
    }


    public function recorderBankSt()
    {
        $userDao = new UserDaoMysql();
        $params = $_POST;
        $toRecord = [];
        $toValidable = [];
        foreach ($params as $param => $value)
        {
            if (substr($param, 0, 8) == 'toRecord')
            {
                if (substr($param, 9, 6) == 'userId')
                {
                    $i = substr($param, 15);
                    $toRecord[$i]['id_user'] = $value;
                }
                if (substr($param, 9, 4) == 'date')
                {
                    $i = substr($param, 13);
                    $toRecord[$i]['entry_date'] = $value;
                }
                if (substr($param, 9, 5) == 'value')
                {
                    $i = substr($param, 14);
                    $toRecord[$i]['value'] = $value;
                }
            }
            if (substr($param, 0, 11) == 'toValidable')
            {
                $toValidable[] = $value;                
            }
        }

        foreach ($toRecord as $itemToRecord)
        {
            extract($itemToRecord);
            $record_user = $_SESSION['userId'];
            $status = 1;
            $img=null;
            $description = "depósito / amorização";
            $id_entry_type = 4;
            $userDao->addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, 
            $status, $img);
        }

        foreach ($toValidable as $idValidated)
        {
            $userDao->deleteValidableEntry($idValidated);
        }

        header('location: index.php?class=ManagementArea');
        exit;
    }


    public function OLDrecordBatchEntries($usersIds,  $dateRecord, $description, $value, $id_entry_type, $status, $opt, $finalDate='', $sub=0)
    {

        $userDao = new UserDaoMysql();
        if ($finalDate == '')
        {
            $finalDate = $dateRecord;
        }
        if ( !is_numeric($sub) )
        {
            $sub = 0;
        }

        $record_user = $_SESSION['userId'];
        $img=null;
        $entryTypes = $userDao->findEntryTypes();
        $sign = 1;
        foreach ($entryTypes as $entryType)
        {
            if ($entryType->getId() == $id_entry_type ) 
            {
                $sign = $entryType->getSign();
            }
        }


        foreach ($usersIds as $userId)
        {
            $u = $userDao->findById($userId);


            if( $type = 'ipca' || 'calc') 
            {
                $statement = $u->getTodayFutureEntries($finalDate)['today'];
                $total = 0;
                foreach ($statement as $item) 
                {
                    $total += $item->getValue();
                }
            }
            switch ($type) {
                case 'ipca':
                    $iIPCA = $value;
                    $value =  $iIPCA * ($total - $sub);   
                    break;
                case 'calc':
                    $expression = $value;
                    $value = eval('$expression' . ';');
                    break;
            }


            if ( ($value >= 0 && $sign < 0) || ( ($value < 0 && $sign > 0) ) )
            {
                $value = $value * -1;
            }
           
            $userDao->addEntry($userId, $dateRecord, $description, $value, $id_entry_type, $record_user, 
                $status, $img);
            
        
        }

    } 


    public function recordBatchEntries()
    {
        $params = $_POST;
        echo "<pre>";
        //print_r($params);
       
        $usersIds = [];
        $expression = isset($params['batchExpression']) ? $params['batchExpression'] : '' ;
        $finalDate = isset($params['batchEntriesDateLimit']) ? $params['batchEntriesDateLimit'] : '';
        $sub = isset($params['batchSub']) ? $params['batchSub'] : '';
        $dateRecord = isset($params['batchEntriesDateRecord']) ? $params['batchEntriesDateRecord'] : '';
        $id_entry_type = isset($params['batchIdEntryType']) ? $params['batchIdEntryType'] : '';
        $description = isset($params['batchDescription']) ? $params['batchDescription'] : '';
        $status = isset($params['batchStatus']) ? $params['batchStatus'] : '';
        $opt = isset($params['batchOptMet']) ?  $params['batchOptMet'] : '';
        $iIPCA = isset($params['batchIPCA']) ? $params['batchIPCA']/100 : '';
        $value = isset($params['batchvalue']) ? $params['batchvalue'] : ''; 
       
        foreach ($params as $key => $param)
        {
            //checkBatchEntries4
            //01234567890123456
            if (substr($key, 0, 17) == 'checkBatchEntries')
            {
                $usersIds[] = substr($key, 17);  
            }
            //echo $key . ' => ' . $param . "<br>"; 
        }


        $userDao = new UserDaoMysql();
        if ($finalDate == '')
        {
            $finalDate = $dateRecord;
        }
        if ( !is_numeric($sub) )
        {
            $sub = 0;
        }

        $record_user = $_SESSION['userId'];
        $img=null;
        $entryTypes = $userDao->findEntryTypes();
        $sign = 1;
        foreach ($entryTypes as $entryType)
        {
            if ($entryType->getId() == $id_entry_type ) 
            {
                $sign = $entryType->getSign();
            }
        }


        foreach ($usersIds as $userId)
        {


           




            $u = $userDao->findById($userId);


            if( $type = 'ipca' || 'calc') 
            {
                echo "finalDate: " . $finalDate; 
                $statement = $u->getTodayFutureEntries($finalDate)['today'];
                $total = 0;
                echo "<pre>";
                print_r($statement);
                $i = 1;
                foreach ($statement as $item) 
                {
                    echo "TOTAL FOREACH {$i} : {$total} <br>";
                    echo "valor: " . $item->getValue() . '<br><br>';
                    $total += $item->getValue();
                    $i++;
                }
            }
            switch ($type) {
                case 'ipca':
                    //$iIPCA = $value;
                    echo '<br>Value Inicial: ' . $value; 
                    echo '<br>IPCA: ' . $iIPCA; 
                    echo '<br>Total: ' . $total;
                    echo '<br>sub: ' . $sub;
                    $value =  round($iIPCA * ($total + $sub), 2);   
                    echo '<br>Value Final: ' . $value; 
                    break;
                case 'calc':
                    //$expression = $value;
                    $value = round(eval('$expression' . ';'), 2);
                    break;
            }


            if ( ($value >= 0 && $sign < 0) || ( ($value < 0 && $sign > 0) ) )
            {
                $value = $value * -1;
            }
        

            // echo "user id:{$userId}, dateRecord:{$dateRecord}, description:{$description},
            // value:{$value}, id_entry_type:{$id_entry_type}, record_user:{$record_user}, status:{$status}, 
            // img:{$img}";
            // $userDao->addEntry($userId, $dateRecord, $description, $value, $id_entry_type, $record_user, 
            //     $status, $img);







            
            
            
            
        
        }



        // print_r($params);
        // echo "<br><br>{$iIPCA}";


    }






}
