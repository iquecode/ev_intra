<?php
require_once 'User.php';
class Entry {
    private $id;   //db id_entry
    private $date; //db entry_date 
    private $timeRecord; //new db record_time
    private $description; // db description
    private $value; // db value
    private $type; //new db id_entry_type  -> fk (references entry_types -> id_entry_type ) 
    private $userRecorder; // db record_user -> fk (references users-> id_user ) 
    private $status; //db status   0-pendente  1-validada
    private $img;  //db img    caminho comprovante de depósito na db
    public function getId(){
        return $this->id;
    }
    public function setId($i){
        $this->id = $i;
    }
    public function getDate(){
        return $this->date;
    }  
    public function setDate($d){
        $this->date = $d;
    }
    public function getTimeRecord(){
        return $this->timeRecord;
    }  
    public function setTimeRecord($t){
        $this->timeRecord = $t;
    }
    public function getDescription(){
        return $this->description;
    }
    public function setDescription($n){
        $this->description = $n;
    }
    public function getValue() {
        return $this->value;
    }
    public function setValue($v){
        $this->value = $v;
    }
    public function getType(){
        return $this->type;
    }  
    public function setType($t){
        $this->type = $t;
    }
    public function getUserRecorder(){
        return $this->userRecorder;
    }  
    public function setUserRecorder($u){
        $this->userRecorder = $u;
    }
    public function getStatus(){
        return $this->status;
    }  
    public function setStatus($s){
        $this->status = $s;
    }
    public function getImg(){
        return $this->img;
    }  
    public function setImg($i){
        $this->img = $i;
    }
}
?>
