<?php

Class PainelFin {

    private $html;
    private $date;
    private $bankAccount;
    private $invest;
    
    public function __construct($date, $bankAccount, $invest)
    {
         $this->html = file_get_contents('html/ecovila/ecovila.html');
         $this->date = $date;
         $this->bankAccount = $bankAccount;
         $this->invest = $invest;
    }

    public function load()
    {
        $this->html = str_replace('{date}',          $this->date,           $this->html);
        $this->html = str_replace('{bank_account}',  $this->bankAccount,    $this->html);
        $this->html = str_replace('{invest}',        $this->invest,         $this->html);
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

}