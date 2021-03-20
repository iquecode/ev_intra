<?php
Class Msg {

    private $msg;
    private $cssClass;
    private $html;

    public function __construct($msg='', $destiny=null, $cssClass='msg_p')
    {
        $this->html = file_get_contents('html/msg/msg.html');
        $this->msg = $msg;
        $this->cssClass = $cssClass;
        $this->destiny = $destiny;
    }

    public function load()
    {
        $this->html = str_replace('{msg}',        $this->msg,      $this->html);
        $this->html = str_replace('{cssClass}',   $this->cssClass, $this->html);
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