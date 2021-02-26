<?php

require_once 'User.php';
class Entry {
    private $id;
    private $date;
    private $description;
    private $value;
    private $userRecorder;
    
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
    public function getUserRecorder() {
        return $this->userRecorder;
    }
    public function setUserRecorder($r){
        $this->userRecorder = $r;
    }
    
}


?>





