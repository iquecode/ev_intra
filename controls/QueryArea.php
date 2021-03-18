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
require_once 'controls/PrivateArea.php';

class QueryArea
{
    private $html;
    
    public function __construct()
    {
        $userDao = new UserDaoMysql();

       
        //melhorar isso
        if (isset($_POST['cota'])) {
            $id = str_replace('u_id', '', $_POST['cota']);
            //$u = $userDao->findByQuota($_POST['cota']);
            $u = $userDao->findById($id); 
            $_SESSION['queryQuota'] = $id;
        } 
        else 
        {
            //$u = $userDao->findByQuota($_SESSION['queryQuota']);     
            $u = $userDao->findById($_SESSION['queryQuota']);
        }
        
        
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
        $statment  = new Statment($todayEntries, $id);
        $stFutures = new Statment($futureEntries, $id, 'futuros', 'Lançamentos Futuros', 'Total lançamentos futuros', false);
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
        $layout = new Layout($title, $css, $js, $content);
        $this->html = $layout->getHTML(); 
        return true; // se encontrar usuário e proseguir, retornar true
    }

    
    public function show()
    {  
        print $this->html; 
    }
}