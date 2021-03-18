<?php
require_once 'controls/parts/AdminOpt.php';
require_once 'controls/parts/Statment.php';

Class StatmentsArea {

    private $html;
    private $allUsers;
    private $select;
    private $statments;
    
    public function __construct($allUsers)
    {
         $this->html = file_get_contents('html/statments_area/statments_area.html');
         $this->allUsers = $allUsers;
         $this->select = new AdminOpt($this->allUsers, 2);
    }

    public function load()
    {
        $content = $this->select->getHTML();
        $statments=[];
        foreach ($this->allUsers as $u) {
            $allEntries = $u->getTodayFutureEntries();    
            //$futureEntries = $allEntries['future'];
            $todayEntries =  $allEntries['today'];
            $statments[$u->getId()]  = new Statment($todayEntries, $u->getId());  
            $content .= $statments[$u->getId()]->getHTML();          
        }
        $content .= file_get_contents('html/management_entries_user/to_include_entry.html');  
        $content .= file_get_contents('html/management_entries_user/management_entries_user.html');
        $this->html = str_replace('{content}',    $content,    $this->html);
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