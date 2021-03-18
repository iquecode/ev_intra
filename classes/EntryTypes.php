<?php
class EntryType {
    private $id;   //db id_entry_type
    private $type; //db type 
    private $sign; //db sign
    public function getId(){
        return $this->id;
    }
    public function setId($i){
        $this->id = $i;
    }
    public function getType(){
        return $this->type;
    }  
    public function setType($t){
        $this->type = $t;
    }
    public function getSign(){
        return $this->sign;
    }  
    public function setSign($s){
        $this->sign = $s;
    }
}
