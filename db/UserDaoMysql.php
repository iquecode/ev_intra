<?php
require_once __DIR__.'/../classes/User.php';
require_once __DIR__.'/../classes/EntryType.php';
require_once __DIR__.'/../config/config.php';

class UserDaoMysql implements UserDao {
    private static $conn;

    // se não existir conexão com o banco de dados, faz conexão e atualiza variável de conexão da classe
    public function __construct()
    {
        if (empty(self::$conn))
        {
            try 
            {
                self::$conn = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOExcepion $e)
            {
                return $e->getMessage();
            } 
        }
        // return self::$conn;
    }


    // avaliar
    public function add(User $u) {
        $sql = self::$conn->prepare('INSERT INTO users (quota, name, nickname, email, pass) 
            VALUES (:quota, :name, :nickname, :email, :pass)');
        $sql->bindValue(':quota', $u->getQuota());
        $sql->bindValue(':name', $u->getName());
        $sql->bindValue(':nickname', $u->getNickname());
        $sql->bindValue(':email', $u->getEmail());
        $sql->bindValue(':pass', $u->getPass());
        $sql->execute();
        //$sql->debugDumpParams();
        $u->setId( self::$conn->lastInsertId() );
        //print_r($u); 
        return $u;
    }
    
    //não é funcional ainda
    public function findAll() {
        //$array = [];

        $data = false;
        $sql = self::$conn->query('SELECT * FROM users');
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll((PDO::FETCH_ASSOC));


            foreach($data as $item) {
                  $u = new User();
                  $u->setId($item['id_user']);
                  $u->setQuota($item['quota']);
                  $u->setName($item['name']);
                  $u->setNickName($item['nickname']);
                  $u->setEmail($item['email']);
                  $u->setPass($item['pass']);
                  $u->setType($item['user_type']);
                  $array[] = $u;
              }          
        }

        return $array;
        //return $data;
    }


    //não é funcional ainda                   
    public function findAllWithEntries() {
        //$array = [];

        $data = false;
        $sql = self::$conn->query('SELECT * FROM users');
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll((PDO::FETCH_ASSOC));


            foreach($data as $item) {
                $u = new User();
                $u->setId($item['id_user']);
                $u->setQuota($item['quota']);
                $u->setName($item['name']);
                $u->setNickName($item['nickname']);
                $u->setEmail($item['email']);
                $u->setPass($item['pass']);
                $u->setType($item['user_type']);
                
                //Pegar array com os lançamentos relacionados com o User
                //$userId = $u->getId();
                $userId = $u->getId();
                $sqlEntries = self::$conn->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
                $sqlEntries->bindValue(':id_user', $userId);
                $sqlEntries->execute();
                $dataEntries = $sqlEntries->fetchAll(PDO::FETCH_ASSOC);
                // echo('<br/>'.'Projetos do UserId '.$userId.' : '.'<br/>');
                //print_r($dataProjects);
                $entries = [];
                foreach($dataEntries as $item) {
                    $e = new Entry();
                    $e->setId($item['id_entry']);
                    $e->setDate($item['entry_date']);
                    $e->setTimeRecord($item['record_time']);
                    $e->setDescription($item['description']);
                    $e->setValue($item['value']);
                    $e->setType($item['id_entry_type']);
                    $e->setUserRecorder($item['record_user']);
                    $e->setStatus($item['status']);
                    $e->setImg($item['img']);
                    $entryId = $e->getId();
                    array_push($entries, $e);
                }
                $u->setEntries($entries);   
                //var_dump($u);  
                $array[] = $u;
              }          
        }

        return $array;
        //return $data;
      
                //$entrys=[]
                //getEntries() 
                //setEntries($e)
      
    }


   
    public function findByEmail($email) {
        $sql = self::$conn->prepare('SELECT * FROM users WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();
            $u = new User();
            $u->setId($data['id_user']);
            $u->setQuota($data['quota']);
            $u->setName($data['name']);
            $u->setNickName($data['nickname']);
            $u->setEmail($data['email']);
            $u->setPass($data['pass']);
            $u->setType($data['user_type']);
            
            //Pegar array com os lançamentos relacionados com o User
            $userId = $u->getId();
            $sqlEntrys = self::$conn->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
            $sqlEntrys->bindValue(':id_user', $userId);
            $sqlEntrys->execute();
            $dataEntrys = $sqlEntrys->fetchAll(PDO::FETCH_ASSOC);

            $entrys = [];
            foreach($dataEntrys as $item) {
                $e = new Entry();
                $e->setId($item['id_entry']);
                $e->setDate($item['entry_date']);
                $e->setTimeRecord($item['record_time']);
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $e->setType($item['id_entry_type']);
                $e->setUserRecorder($item['record_user']);
                $e->setStatus($item['status']);
                $e->setImg($item['img']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntries($entrys);
           
            // var_dump($u);
            return $u;

        } else {
            return false;
        }
    }

    public function findById($id) {
        $sql = self::$conn->prepare('SELECT * FROM users WHERE id_user = :id_user');
        $sql->bindValue(':id_user', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();      
            $u = new User();
            $u->setId($data['id_user']);
            $u->setQuota($data['quota']);
            $u->setName($data['name']);
            $u->setNickName($data['nickname']);
            $u->setEmail($data['email']);
            $u->setPass($data['pass']);
            $u->setType($data['user_type']);
            
            //Pegar array com os lançamentos relacionados com o User
            //$userId = $u->getId();
            $userId = $u->getId();
            $sqlEntrys = self::$conn->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
            $sqlEntrys->bindValue(':id_user', $userId);
            $sqlEntrys->execute();
            $dataEntrys = $sqlEntrys->fetchAll(PDO::FETCH_ASSOC);
            // echo('<br/>'.'Projetos do UserId '.$userId.' : '.'<br/>');
            //print_r($dataProjects);
            $entrys = [];
            foreach($dataEntrys as $item) {
                $e = new Entry();
                $e->setId($item['id_entry']);
                $e->setDate($item['entry_date']);
                $e->setTimeRecord($item['record_time']);
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $e->setType($item['id_entry_type']);
                $e->setUserRecorder($item['record_user']);
                $e->setStatus($item['status']);
                $e->setImg($item['img']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntries($entrys);   
            //var_dump($u);
            return $u;
        } else {
            //echo 'findByEmail - false';
            return false;
        } 
    }


    public function findByQuota($quota) {
        $sql = self::$conn->prepare('SELECT * FROM users WHERE quota = :quota');
        $sql->bindValue(':quota', $quota);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();      
            $u = new User();
            $u->setId($data['id_user']);
            $u->setQuota($data['quota']);
            $u->setName($data['name']);
            $u->setNickName($data['nickname']);
            $u->setEmail($data['email']);
            $u->setPass($data['pass']);
            $u->setType($data['user_type']);
            
            //Pegar array com os lançamentos relacionados com o User
            //$userId = $u->getId();
            $userId = $u->getId();
            $sqlEntrys = self::$conn->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
            $sqlEntrys->bindValue(':id_user', $userId);
            $sqlEntrys->execute();
            $dataEntrys = $sqlEntrys->fetchAll(PDO::FETCH_ASSOC);
            // echo('<br/>'.'Projetos do UserId '.$userId.' : '.'<br/>');
            //print_r($dataProjects);
            $entrys = [];
            foreach($dataEntrys as $item) {
                $e = new Entry();
                $e->setId($item['id_entry']);
                $e->setDate($item['entry_date']);
                $e->setTimeRecord($item['record_time']);
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $e->setType($item['id_entry_type']);
                $e->setUserRecorder($item['record_user']);
                $e->setStatus($item['status']);
                $e->setImg($item['img']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntries($entrys);   
            //var_dump($u);
            return $u;
        } else {
            //echo 'findByEmail - false';
            return false;
        } 
    }



    public function update(User $u) {
       $sql = self::$conn->prepare('UPDATE users SET nickname = :nickname, email = :email, 
       pass = :pass WHERE id_user = :id');
       $sql->bindValue(':nickname', $u->getNickname());
       $sql->bindValue(':email', $u->getEmail());
       $sql->bindValue(':pass', $u->getPass());
       $sql->bindValue(':id', $u->getId());
       $sql->execute();
       return true;
    }

    public function delete($id) {
        $sql = self::$conn->prepare('DELETE FROM users WHERE id=:id');
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

    //Adicionar um lançamento relacionado ao usuário
    public function addEntry($id_user, $entry_date, $description, $value, $id_entry_type, $record_user, $status, $img) {
        $sql = self::$conn->prepare('INSERT INTO entrys 
            (entry_date, description, value, id_user, id_entry_type, record_user, status, img) 
            VALUES (:entry_date, :description, :value, :id_user, :id_entry_type, :record_user, :status, :img)');
        $sql->bindValue(':entry_date', $entry_date);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':value', $value);
        $sql->bindValue(':id_user', $id_user);
        $sql->bindValue(':id_entry_type', $id_entry_type);
        $sql->bindValue(':record_user', $record_user);
        $sql->bindValue(':status', $status);
        $sql->bindValue(':img', $img);
        $sql->execute();
        //echo "<pre>";
        //$sql->debugDumpParams();
        //$u->setId( self::$conn->lastInsertId() );
        //print_r($u); 
        $e = new Entry();
        $e->setId( self::$conn->lastInsertId() );
        return $e;
    }

    //ultima data de concilhação e posição financeira
    public function findParams() {
        $sql = self::$conn->prepare('SELECT * FROM params WHERE id = :id');
        $sql->bindValue(':id', 1);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();  
            $paramsObj = new stdClass; 
            $paramsObj->lastCheck = $data['last_check'];
            $paramsObj->account = $data['account'];
            $paramsObj->invest = $data['invest'];
            return $paramsObj;
        } else {
            //echo 'findByEmail - false';
            return false;
        } 
    }

    public function findEntryTypes() {
        $data = false;
        $sql = self::$conn->query('SELECT * FROM entry_types');
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll((PDO::FETCH_ASSOC));
            foreach($data as $item) {
                   $et = new EntryType();
                   $et->setId($item['id_entry_type']);
                   $et->setType($item['type']);
                   $et->setSign($item['sign']);
                   $array[] = $et;
                //echo "<pre>";
                //print_r($item);
              }  
            //   echo "<pre>";
            //   print_r($array);        
        }
        return $array;
    }

    public function login($email, $pass) {
        $sql = self::$conn->prepare("SELECT id_user FROM users WHERE email = :email AND pass = :pass");
        $sql->bindValue(":email", $email);
        $sql->bindValue(":pass", md5($pass)); //melhorar criptografia da senha
        $sql->execute();
        if($sql->rowCount() > 0 ) {
             //entrar no sistema (sessao)
             $data = $sql->fetch();
             session_start();
             $_SESSION['userId'] = $data['id_user'];
             return true; //logado com sucesso
         } else {
             //implementar segurança no logim:
             //ver se achou apenas o email... 
             //caso sim, verificar se tentativas já se esgotaram - 5 - 
             //se não se esgotaram, pegar no db o número de tentativas e somar + 1 guardando a info no db
             //caso se esgotaram, gravar a info do timestamp no db e retornar a espera de x minutos para novo login 
             //fazer isso em nova função login - login 2 - 
             //verificar a necessidade de alterar a senha
             return false; //não foi possível logar
          }
    }


}