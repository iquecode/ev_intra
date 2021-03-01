<?php
require_once 'classes/User.php';

class UserDaoMysql implements UserDao {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }


    public function add(User $u) {
        $sql = $this->pdo->prepare('INSERT INTO users (quota, name, nickname, email, pass) 
            VALUES (:quota, :name, :nickname, :email, :pass)');
        $sql->bindValue(':quota', $u->getQuota());
        $sql->bindValue(':name', $u->getName());
        $sql->bindValue(':nickname', $u->getNickname());
        $sql->bindValue(':email', $u->getEmail());
        $sql->bindValue(':pass', $u->getPass());
        $sql->execute();
        //$sql->debugDumpParams();
        $u->setId( $this->pdo->lastInsertId() );
        //print_r($u); 
        return $u;
    }
    
    public function findAll() {
        $array = [];

        $sql = $this->pdo->query('SELECT * FROM users');
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll();

            foreach($data as $item) {
                $u = new User();
                $u->setId($item['id']);
                $u->setNome($item['nome']);
                $u->setEmail($item['email']);
                $array[] = $u;
            }          
        }

        return $array;
    }

    public function findByEmail($email) {
        $sql = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch();
            $u = new User();
            $u->setId($data['id_user']);
            $u->setQuota($data['quota']);
            $u->setName($data['name']);
            $u->setName($data['nickname']);
            $u->setEmail($data['email']);
            $u->setPass($data['pass']);
            $u->setType($data['user_type']);
            
            //Pegar array com os lançamentos relacionados com o User
            $userId = $u->getId();
            $sqlEntrys = $this->pdo->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
            $sqlEntrys->bindValue(':id_user', $userId);
            $sqlEntrys->execute();
            $dataEntrys = $sqlEntrys->fetchAll(PDO::FETCH_ASSOC);

            $entrys = [];
            foreach($dataEntrys as $item) {
                $e = new Entry();
                $e->setId($item['id_entry']);
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntrys($entrys);
           
            // var_dump($u);
            return $u;

        } else {
            return false;
        }
    }

    public function findById($id) {
        $sql = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user');
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
            $sqlEntrys = $this->pdo->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
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
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $e->setUserRecorder($item['record_time']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntrys($entrys);   
            //var_dump($u);
            return $u;
        } else {
            //echo 'findByEmail - false';
            return false;
        } 
    }


    public function findByQuota($quota) {
        $sql = $this->pdo->prepare('SELECT * FROM users WHERE quota = :quota');
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
            $sqlEntrys = $this->pdo->prepare('SELECT * FROM entrys WHERE id_user = :id_user');
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
                $e->setDescription($item['description']);
                $e->setValue($item['value']);
                $e->setUserRecorder($item['record_time']);
                $entryId = $e->getId();
                array_push($entrys, $e);
            }
            $u->setEntrys($entrys);   
            //var_dump($u);
            return $u;
        } else {
            //echo 'findByEmail - false';
            return false;
        } 
    }



    public function update(User $u) {
       $sql = $this->pdo->prepare('UPDATE users SET nickname = :nickname, email = :email, 
       pass = :pass WHERE id_user = :id');
       $sql->bindValue(':nickname', $u->getNickname());
       $sql->bindValue(':email', $u->getEmail());
       $sql->bindValue(':pass', $u->getPass());
       $sql->bindValue(':id', $u->getId());
       $sql->execute();
       return true;
    }

    public function delete($id) {
        $sql = $this->pdo->prepare('DELETE FROM users WHERE id=:id');
        $sql->bindValue(':id', $id);
        $sql->execute();
    }

    public function login($email, $pass) {
        $sql = $this->pdo->prepare("SELECT id_user FROM users WHERE email = :email AND pass = :pass");
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