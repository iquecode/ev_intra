<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');

Class ListValidate {

    private $html;
    private $validableEntries;

   
    public function __construct($validableEntries=[])
    {
        
         $template = $_SERVER['DOCUMENT_ROOT'] . '/ev_intra/html/management_area/list_validate/list_validate.html';   
         $this->html = file_get_contents($template);
         $this->validableEntries = $validableEntries;
    }

    public function load()
    {

        $items = '';
        $dao = new UserDaoMysql;
        
        
        
        $msg =   empty($this->validableEntries) ? 'Não existem depósitos a validar' : 'Depósitos de ecovileir@s a validar';
        $class = empty($this->validableEntries) ? 'hide' : 'form_list_validate';

        foreach ($this->validableEntries as $e) 
        {             
            $date = date('d/m/Y',strtotime($e['entry']->getDate()));
            $value = $e['entry']->getValue();
            
            $template_item = $_SERVER['DOCUMENT_ROOT'] . '/ev_intra/html/management_area/list_validate/list_item.html';
            $item = file_get_contents($template_item);
            $item = str_replace( '{date}',    $date,             $item);
            $item = str_replace( '{value}',   num($value),       $item);
            $item = str_replace( '{quota}',   $e['user_info'],   $item);                    
            $items .= $item;        
        }
        $this->html = str_replace('{class}',    $class, $this->html);
        $this->html = str_replace('{items}',    $items, $this->html);
        $this->html = str_replace('{msg}',      $msg,   $this->html);
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