<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'controls/parts/Statment.php';
require_once 'controls/parts/Welcome.php';
require_once 'controls/parts/PainelFin.php';
require_once 'controls/parts/BankData.php';
require_once 'controls/parts/Opt.php';
require_once 'controls/parts/AdminOpt.php';
require_once 'controls/Layout.php';

class PrivateArea
{
    private $html;
    
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
        $allEntries = $u->getTodayFutureEntries();    
        $futureEntries = $allEntries['future'];
        $todayEntries =  $allEntries['today'];

        $welcome   = new Welcome($nickname, $quota, $name);
        $statment  = new Statment($todayEntries, $id);
        $stFutures = new Statment($futureEntries, $id, 'futuros', 'Lançamentos Futuros', 'Total lançamentos futuros', false);
        $painelFin = new PainelFin(date('d/m/Y', strtotime($params->lastCheck)), num($params->account), num($params->invest));
        $bankData = new BankData($date_min, $date_max);
        $opt = new Opt('index.php?class=UpdateArea','Alterar senha e confirmar dados', 'sair.php', 'Sair');
        $content = $welcome->getHTML() . $statment->getHTML() . $stFutures->getHTML() . $painelFin->getHTML() .
                $bankData->getHTML() . $opt->getHTML();

        if ($isAdmin) {
            //se usuário tiver permissão de administração, percorrrer o db para pegar o número da cota
        // apelido e nome de todos usuários, para colocar no select para consulta no final da página
            $allUsers = $userDao->findAll(); // <-aqui
            $adminOpt = new AdminOpt($allUsers);
            $content .= $adminOpt->getHTML(); 
        } 

        $title = 'Área Ecovileir@';
        $css ='css/style_content.css';
        $js ='js/scriptAreaPrivativa.js';
        $this->html = new Layout($title, $css, $js, $content);
    }

    public function show()
    {
        $this->html->show();
        //print 'Olá... eu sou a área privada!!! : )';
    }
}