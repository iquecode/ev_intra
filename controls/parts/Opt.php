<?php

Class Opt {

    private $html;
    private $link1;
    private $label1;
    private $link2;
    private $label2;
    
    public function __construct($link1, $label1, $link2, $label2)
    {
         $this->html = file_get_contents('html/opt/opt.html');
         $this->link1 = $link1;
         $this->label1 = $label1;
         $this->link2 = $link2;
         $this->label2 = $label2;
    }

    public function load()
    {
        $this->html = str_replace('{link1}',      $this->link1,    $this->html);
        $this->html = str_replace('{link2}',      $this->link2,    $this->html);
        $this->html = str_replace('{label1}',     $this->label1,   $this->html);
        $this->html = str_replace('{label2}',     $this->label2,   $this->html);
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
