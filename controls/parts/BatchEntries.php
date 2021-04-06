<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIR_BASE . 'db/UserDaoMysql.php');

Class BatchEntries {

    private $html;
    private $allUsers;
    private $entryTypes;
    //private $validableEntries;
    //private $type;

    public function __construct($allUsers, $entryTypes)
    {
         $template = $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . "html/management_area/batchEntries/batchEntries.html";   
         $this->html = file_get_contents($template);
         $this->allUsers = $allUsers;
         $this->entryTypes = $entryTypes; 
         //$this->validableEntries = $validableEntries;
         //$this->type = $type;
    }


    public function load()
    {

        $typeEntryOptHTML = '';
        foreach ($this->entryTypes as $et) 
        {
            $idType = $et->getId();
            $type = $et->getType();
            $sing = $et->getSign();
            $deb_cred = $sing > 0 ? '| Credito |' : "| Debito. |";
            $typeEntryOptHTML = $typeEntryOptHTML . "<option value={$idType}>{$deb_cred}    {$type}</option>";
        }

        $itemsQuotaHTML = '';
        $itemQuotaTemplate = $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . "html/management_area/batchEntries/batchEntriesItemQuota.html";
        $itemQuota = file_get_contents($itemQuotaTemplate);
        foreach ($this->allUsers as $u) 
        {
            $id = $u->getId();
            $q = $u->getQuota();
            $nn = $u->getNickName();
            $n = $u->getName();
            $item = $itemQuota;
            $quota = "{$q} - {$nn} - {$n}";
            $item = str_replace('{quota}',    $quota,    $item);
            $item = str_replace('{id_user}',  $id,    $item);
            $itemsQuotaHTML = $itemsQuotaHTML . $item;
        }

        $this->html = str_replace('{type_entry_options}',     $typeEntryOptHTML,    $this->html);
        $this->html = str_replace('{items_quota}',     $itemsQuotaHTML,    $this->html);
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
