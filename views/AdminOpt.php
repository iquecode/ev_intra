<?php

Class AdminOpt {

    private $html;
    private $allUsers;
    
    public function __construct($allUsers)
    {
         $this->html = file_get_contents('html/admin_opt/admin_opt.html');
         $this->allUsers = $allUsers;
    }

    public function load()
    {
        $optionsHTML = '';
        foreach ($this->allUsers as $u) {
            $q = $u->getQuota();
            $nn = $u->getNickName();
            $n = $u->getName();
            $optionsHTML = $optionsHTML . "<option value={$q}>{$q} - {$nn} - {$n}</option>";
        }
        $this->html = str_replace('{options}',    $optionsHTML,    $this->html);
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