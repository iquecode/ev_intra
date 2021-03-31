<?php

Class Welcome {

    private $html;
    private $nickname;
    private $quota;
    private $name;
    
    public function __construct($nickname, $quota, $name, $template=1)
    {
         $this->html = file_get_contents("html/welcome/welcome{$template}.html");
         $this->nickname = $nickname;
         $this->quota = $quota;
         $this->name = $name;
    }

    public function load()
    {
        $total=0;
        $items = '';
        $this->html = str_replace('{nickname}', $this->nickname,    $this->html);
        $this->html = str_replace('{quota}',    $this->quota,       $this->html);
        $this->html = str_replace('{name}', $this->name,    $this->html);
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
