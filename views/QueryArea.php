<?php
require_once 'db/UserDaoMysql.php';
require_once 'helper.php';
require_once 'views/Statment.php';
require_once 'views/Welcome.php';
require_once 'views/PainelFin.php';
require_once 'views/BankData.php';
require_once 'views/Opt.php';
require_once 'views/AdminOpt.php';
require_once 'views/Layout.php';

class QueryArea
{
    private $html;
    
    public function __construct()
    {
        $userDao = new UserDaoMysql();
        $u = $userDao->findByQuota($_POST['cota']);
        if(!$u) {    // se não encontrar usuário com o número da cota, retornar false
            return false;
        }

        $id = $u->getId();
        $nickname = $u->getNickname();
        $quota = $u->getQuota();
        $name = $u->getName();
        $isAdmin = ((int)$u->getType()) == 1;

        $allEntries = $u->getTodayFutureEntries();    
        $futureEntries = $allEntries['future'];
        $todayEntries =  $allEntries['today'];

        $welcome   = new Welcome($nickname, $quota, $name, 2);
        $statment  = new Statment($todayEntries);
        $stFutures = new Statment($futureEntries, 'futuros', 'Lançamentos Futuros', 'Total lançamentos futuros', false);
        $opt = new Opt('index.php?class=PrivateArea','Voltar', 'sair.php', 'Sair');

        $content = $welcome->getHTML() . $statment->getHTML() . $stFutures->getHTML() . $opt->getHTML();

        if ($isAdmin) {
            //se usuário tiver permissão de administração, percorrrer o db para pegar o número da cota
            // apelido e nome de todos usuários, para colocar no select para consulta no final da página
            $allUsers = $userDao->findAll(); // <-aqui
            $adminOpt = new AdminOpt($allUsers);
            $content .= $adminOpt->getHTML(); 
        } 

        $title = 'Consulta Administrativa';
        $css ='css/style_content.css';
        $js ='js/scriptAreaPrivativa.js';
        $this->html = new Layout($title, $css, $js, $content);
        return true; // se encontrar usuário e proseguir, retornar true
    }

    
    public function show()
    {
        $this->html->show();
    }
}