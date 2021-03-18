<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'controls/Layout.php';
require_once 'controls/StatmentsArea.php';


class ManagementArea
{
    private $html;
    private $allUsers;
    
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
        


        //echo "<pre>";
        //print_r($allUsers);
        //$allEntries = $u->getTodayFutureEntries();    
        //$futureEntries = $allEntries['future'];
        //$todayEntries =  $allEntries['today'];

        //$welcome   = new Welcome($nickname, $quota, $name);
        //$statment  = new Statment($todayEntries);
        //$stFutures = new Statment($futureEntries, 'futuros', 'Lançamentos Futuros', 'Total lançamentos futuros', false);
        //$painelFin = new PainelFin(date('d/m/Y', strtotime($params->lastCheck)), num($params->account), num($params->invest));
        //$bankData = new BankData($date_min, $date_max);
        //$opt = new Opt('index.php?class=UpdateArea','Alterar senha e confirmar dados', 'sair.php', 'Sair');
        //$content = $welcome->getHTML() . $statment->getHTML() . $stFutures->getHTML() . $painelFin->getHTML() .
                //$bankData->getHTML() . $opt->getHTML();

        //if ($isAdmin) {
            //se usuário tiver permissão de administração, percorrrer o db para pegar o número da cota
        // apelido e nome de todos usuários, para colocar no select para consulta no final da página
        //    $allUsers = $userDao->findAll(); // <-aqui
        //    $adminOpt = new AdminOpt($allUsers);
        //    $content .= $adminOpt->getHTML(); 
        //} 

       
    }


    public function load()
    {
        

        //$content = file_get_contents('html/management/management.html');
        $title = 'Área de Administração';
        $css ='css/style_management.css';
        $js ='js/jsManagementArea.js';
        //$js = str_replace('{name}',     'TESTE',          $js);
        
        $statmentsArea = new StatmentsArea($this->allUsers);
        $content = $statmentsArea->getHTML();
        
        $this->html = new Layout($title, $css, $js, $content, 2);





        // $this->authError = $this->error == 1 ? 'block' : 'none'; 
        // $this->fillError = $this->error == 2 ? 'block' : 'none'; 
        // $this->html = str_replace('{version}',     VERSION,          $this->html);
        // $this->html = str_replace('{yyyy}',        YEAR,             $this->html);
        // $this->html = str_replace('{auth_error}',  $this->authError, $this->html);
        // $this->html = str_replace('{fill_error}',  $this->fillError, $this->html);
    }

    public function show()
    {
        $this->load();
        $this->html->show();
        //print 'Olá... eu sou a área privada!!! : )';
    }
}