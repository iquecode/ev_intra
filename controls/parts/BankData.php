<?php

Class BankData {

    private $html;
    private $dateMin;
    private $dateMax;
    
    public function __construct($dateMin, $dateMax, $template=1)
    {
         $this->html = file_get_contents("html/dados_banc/dados_banc{$template}.html");
         $this->dateMin = $dateMin;
         $this->dateMax = $dateMax;
    }

    public function load()
    {
        $this->html = str_replace('{date_min}',          $this->dateMin,           $this->html);
        $this->html = str_replace('{date_max}',  $this->dateMax,    $this->html);
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
