<?php

Class Statment {

    private $html;
    private $entries;
    private $class;
    private $title;
    private $strTotal;
    private $check;
    private $userId;

    public function __construct($entries=[], $userId, $class='extrato', $title='Demonstrativo Financeiro',
                                $strTotal='Saldo atual', $check=true)
    {
         $file = $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . 'html/statment/statment.html';   
         $this->html = file_get_contents($file);
         $this->entries = $entries;
         $this->userId = $userId;
         $this->class = $class;
         $this->title = $title;
         $this->strTotal = $strTotal;
         $this->check = $check;
    }

    public function load()
    {
        $total=0;
        $items = '';
        $findPend = false;
        foreach ($this->entries as $entry) 
        {         
            // extrato normal
            $date = date('d/m/Y',strtotime($entry->getDate()));
            $description = $entry->getDescription();
            $value = $entry->getValue();
            $neg_pos = $value < 0 ? 'num neg_future' : 'num';

            $file = $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . 'html/statment/statment_item.html';
            $item = file_get_contents($file);
            $item = str_replace( '{date}',          $date,          $item);
            $pend = '';
            
            $status = $entry->getStatus();
            if ($status == 0) {
                $pend = 'pend';
                $findPend = true;
                $description .= ' - a validar';
            }
            
            $item = str_replace( '{description}',   $description,   $item);
            $item = str_replace( '{pend}',          $pend,          $item);            
            $item = str_replace( '{value}',         num($value),    $item);
            $item = str_replace( '{neg_pos}',       $neg_pos,       $item);            
            $total = $total + $value;
            // armazena lançamentos futuros  
            $items .= $item;         
        }

        $stat = $this->html;
        $stat = str_replace('{class}',    $this->class,    $stat);
        $stat = str_replace('{id}',       "u_id" . $this->userId,    $stat);
        $stat = str_replace('{title}',    $this->title,    $stat);
        $stat = str_replace('{items}',    $items,          $stat);
        $stat = str_replace('{neg_pos}',  $neg_pos,        $stat);
        $stat = str_replace('{strTotal}', $this->strTotal,  $stat);
        $stat = str_replace('{total}',    num($total),     $stat);

        $link = "<a class='a_small' href='_changes.php'>Alterar / excluir lançamentos a validar.</a>";
        if ($this->check && $findPend)
        {
            $stat = str_replace('{link}',    $link,     $stat);
        }
        else
        {
            $stat = str_replace('{link}',    '',     $stat);
        }
        
        $this->html = $stat;  
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