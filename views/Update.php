<?php

Class Update {

    private const ERROR =    ['',
                              'Email já cadastrado!!',
                              'Senha e Confirmar senha não correspondem!',
                              'Preencha todos os campos!'];

    private $html;
    private $quota;
    private $nickname;
    private $name;
    private $email;
    private $error;
    private $displayError;
    
    public function __construct($quota, $nickname, $name, $email)
    {
         $this->html = file_get_contents('html/update/update.html');
         $this->quota = $quota;
         $this->nickname = $nickname;
         $this->name = $name;
         $this->email = $email;

         $this->error = 0;   //0 nenhum erro ERROR[0]='' 1..3 elementos da constante de classe ERROR
         $this->displayError = 'none';
    }

    public function load()
    {
        
        $e = $this->error;
        $this->displayError = 'none';
        if ($e==1 || $e==2 || $e==3 ) {
            $this->displayError = 'block';
        }
        else 
        {
            $this->error=0;
        }

        
        $this->html = str_replace('{nickname}', $this->nickname,            $this->html);
        $this->html = str_replace('{quota}',    $this->quota,               $this->html);
        $this->html = str_replace('{name}',     $this->name,                $this->html);
        $this->html = str_replace('{email}',    $this->email,               $this->html);
        $this->html = str_replace('{msg_erro}', self::ERROR[$this->error],  $this->html);
        $this->html = str_replace('{disp}',     $this->displayError,        $this->html);
    }

    public function show() 
    {
        $this->load();
        print $this->html;
    }

    public function getHTML() {
        $this->load();
        return $this->html;
    }

    public function setError($error) { //0 nenhum erro ERROR[0]='' 1..3 elementos da constante de classe ERROR
        $this->error = $error;
    }

    public function getError($i) {
        return self::ERROR[$this->error];
        //return $this->error;
    }

}