<?php

Class AdminOpt {

    private $html;
    private $allUsers;
    
    public function __construct($allUsers, $template=1, $id=1234)
    {
         $this->html = file_get_contents("html/admin_opt/admin_opt{$template}.html");
         $this->allUsers = $allUsers;
         $this->id = $id;
    }

    public function load()
    {
        $optionsHTML = '';
        foreach ($this->allUsers as $u) {
            $id = $u->getId();
            $q = $u->getQuota();
            $nn = $u->getNickName();
            $n = $u->getName();
            
            $optionsHTML = $optionsHTML . "<option value=u_id{$id}>{$q} - {$nn} - {$n}</option>";
        }
        $this->html = str_replace('{options}',    $optionsHTML,    $this->html);
        $this->html = str_replace('{id}',    $this->id,    $this->html);
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
