<?php
require_once 'views/Layout.php';
require_once 'views/Login.php';
require_once 'db/UserDaoMysql.php';

class LoginPage
{
    private $html;
    //private $data;
    
    public function __construct()
    {
        $this->html = file_get_contents('html/login/login.html');
        // $this->data = [ 'id'        => null,
        //                 'nome'      => null,
        //                 'endereco'  => null,
        //                 'bairro'    => null,
        //                 'telefone'  => null,
        //                 'email'     => null,
        //                 'id_cidade' => null];
        $login = new Login();
        if (isset($_POST['email'])) 
        {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
            if( !empty($email) && !empty($senha) ) 
            {
                $userDao = new UserDaoMysql();
                if($userDao->login($email,$senha))
                 {
                    header("location: areaPrivada.php");
                } 
                else 
                {
                    $login->setError(1);          
                }
            } 
            else 
            {
                $login->setError(2);
            }
        }
        $content = $login->getHTML();
        $title = 'Login';
        $css ='css/style.css';
        $js ='';
        $index = new Layout($title, $css, $js, $content);
        $this->html = $index->getHTML();
    }

    
    
    public function show()
    {
        print $this->html;
    }
}


















// require_once 'views/Layout.php';
// require_once 'views/Login.php';
// require_once 'db/UserDaoMysql.php';

// $login = new Login();
// if (isset($_POST['email'])) {
//     $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
//     $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
//     //verificar se esta preenchido
//     if( !empty($email) && !empty($senha) ) {
//         $userDao = new UserDaoMysql();
//         if($userDao->login($email,$senha)){
//             header("location: areaPrivada.php");
//         } else {
//             $login->setError(1);          
//         }
//     } else {
//         $login->setError(2);
//     }
// }
// $content = $login->getHTML();
// $title = 'Login';
// $css ='css/style.css';
// $js ='';
// $index = new Layout($title, $css, $js, $content);
// $index->show();