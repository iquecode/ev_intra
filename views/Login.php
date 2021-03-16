<?php

Class Login {

    private $html;
    private $authError;
    private $fillError;
    private $error;
    
    public function __construct()
    {
         $this->html = file_get_contents('html/login/login.html');
         $this->authError = 'none';
         $this->fillError = 'none';
         $this->error = 0;
    }

    public function load()
    {
        
        $this->authError = $this->error == 1 ? 'block' : 'none'; 
        $this->fillError = $this->error == 2 ? 'block' : 'none'; 
        $this->html = str_replace('{version}',     VERSION,          $this->html);
        $this->html = str_replace('{yyyy}',        YEAR,             $this->html);
        $this->html = str_replace('{auth_error}',  $this->authError, $this->html);
        $this->html = str_replace('{fill_error}',  $this->fillError, $this->html);
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

    public function setError($error) { //0 nenhum erro   1 authError     2 fillError
        $this->error = $error;
    }

}
