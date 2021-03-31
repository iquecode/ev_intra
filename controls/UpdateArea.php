<?php
require_once 'db/UserDaoMysql.php'; 
require_once 'helper.php';
require_once 'controls/Layout.php';
require_once 'controls/parts/Update.php';

class UpdateArea
{
    private $html;
    

    public function __construct()
    {
        $userDao = new UserDaoMysql();
        $id = $_SESSION['userId'];
        $u = $userDao->findById($id);
        $quota = $u->getQuota();
        $nickname = $u->getNickName();
        $name = $u->getName();
        $email = $u->getEmail();
        $update = new Update($quota, $nickname, $name, $email);

        if (isset($_POST['pass'])) 
        {
            $nickname = filter_input(INPUT_POST, 'nickname', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);
            $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING);
           
            //verificar se esta preenchido
            if(!empty($nickname) && !empty($email) && !empty($pass) && 
            !empty($pass2)) 
            {
                $userDao = new UserDaoMysql();
                if($pass == $pass2) 
                {
                    $u2 = $userDao->findByEmail($email);  
                    if( !$u2 || ($u2->getId() == $u->getId()) ) 
                    {
                        $u->setNickname($nickname);
                        $u->setEmail($email);
                        $u->setPass( md5($pass) );
                        $userDao->update( $u );   
                        //Alteração bem sucedida! 
                        header("location: sucess.php");
                        exit;   
                    } 
                    else  
                    {
                        //Email já cadastrado!!
                        $update->setError(1);    
                    }  
                } 
                else 
                {
                    //Senha e Confirmar senha não correspondem!
                    $update->setError(2);
                }
            }
            else 
            {
                //Preencha todos os campos!
                $update->setError(3);       
            } 
        }

        $content = $update->getHTML();
        $title = 'Alterar e confirmar dados';
        $css ='css/style.css';
        $js ='';
        $this->html = new Layout($title, $css, $js, $content);
    }

    public function show()
    {
        $this->html->show();
    }

}
