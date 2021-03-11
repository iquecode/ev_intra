<?php
require_once 'Entry.php';
class User {
    private $id; 
    private $quota;
    private $name;
    private $nickname;
    private $email;         
    private $pass;
    private $type;
    private $entrys=[];
    
    public function getId(){
        return $this->id;
    }
    public function setId($i){
        $this->id = trim($i);
    }
    public function getQuota(){
        
        return $this->quota;
    }
    public function setQuota($q){
        $this->quota = trim($q);
    }
    public function getName(){
        return $this->name;
    }
    public function setName($n){
        $nTemp = strtolower(trim($n));
        $this->name = ucwords($nTemp);
    }
    public function getNickname(){
        return $this->nickname;
    }
    public function setNickname($n){
        $nTemp = strtolower(trim($n));
        $this->nickname = ucwords($nTemp);
    }
    public function getEmail() {
        return $this->email;
    }
    public function setEmail($e){
        $this->email = strtolower(trim($e));
    }
    public function getPass(){
        return $this->pass;
    }
    public function setPass($i){
        $this->pass = trim($i);
    }
    public function getType(){
        return $this->type;
    }
    public function setType($t){
        $this->type = trim($t);
    }
    public function getEntrys() {
        return $this->entrys;
    }
    public function setEntrys($e){
        $this->entrys = $e;
    }
}

interface UserDao {
    public function add(User $u);
    public function findAll();
    public function findByEmail($email);
    public function findById($id);
    public function update(User $u);
    public function delete($id);
    public function login($email, $pass);
}