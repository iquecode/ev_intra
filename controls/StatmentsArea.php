<?php
require_once 'controls/parts/AdminOpt.php';
require_once 'controls/parts/Statment.php';
require_once 'db/UserDaoMysql.php';

Class StatmentsArea 
{
    private $html;
    private $allUsers;
    private $select;
    private $statments;
    private $entryTypes;
    

    public function __construct($allUsers, $entryTypes)
    {
         $this->html = file_get_contents('html/statments_area/statments_area.html');
         $this->allUsers = $allUsers;
         $this->select = new AdminOpt($this->allUsers, 2);
         $this->entryTypes = $entryTypes; 
    }


    public function load()
    {
        $painelEntryUser = file_get_contents('html/management_entries_user/management_entries_user.html');
        $optionsHTML = '';

        foreach ($this->entryTypes as $et) 
        {
            $idType = $et->getId();
            $type = $et->getType();
            $sing = $et->getSign();
            $deb_cred = $sing > 0 ? '| Credito |' : "| Debito. |";
            $optionsHTML = $optionsHTML . "<option value={$idType}>{$deb_cred}    {$type}</option>";
        }
        
        $painelEntryUser = str_replace('{options}',    $optionsHTML,     $painelEntryUser);
        $content = $this->select->getHTML();
        $statments=[];
        
        foreach ($this->allUsers as $u) {
            $allEntries = $u->getTodayFutureEntries();    
            $todayEntries =  $allEntries['today'];
            $statments[$u->getId()]  = new Statment($todayEntries, $u->getId());  
            $content .= $statments[$u->getId()]->getHTML();
        }
       
        $content .= file_get_contents('html/management_entries_user/to_include_entry.html');  
        $content .= $painelEntryUser;
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
