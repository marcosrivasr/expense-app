<?php

class User extends Model implements IModel{
    private $id;
    private $username;
    private $password;
    private $role;
    private $budget;
    private $photo;
    private $name;

    public function __construct(){
        parent::__construct();

        $this->username = '';
        $this->password = '';
        $this->role = '';
        $this->budget = 0.0;
        $this->photo = '';
        $this->name = '';
    }

    

    public function save(){
        try{
            $query = $this->db->connect()->prepare('INSERT INTO users (username, password, role, budget, photo, name) VALUES(:username, :password, :role, :budget, :photo, :name )');
            $query->execute([
                'username' => $this->username, 
                'password' => $this->password,
                'role' => $this->role,
                'budget' => $this->budget,
                'photo' => $this->photo,
                'name' => $this->name
                ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    } 

    public function getAll(){
        $items = [];

        try{
            $query = $this->db->connect()->query('SELECT * FROM users');

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new User();
                $item->setId($p['id']);
                $item->setUsername($p['username']);
                $item->setPassword($p['password'], false);
                $item->setRole($p['role']);
                $item->setBudget($p['budget']);
                $item->setPhoto($p['photo']);
                $item->setName($p['name']);
                

                array_push($items, $item);
            }

            //return $query->fetchAll(PDO::FETCH_ASSOC);
            return $items;


        }catch(PDOException $e){
            echo $e;
        }
    }

    /**
     *  Gets an item
     */
    public function get($id){
        try{
            $query = $this->db->connect()->prepare('SELECT * FROM users WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            $this->budget = $user['budget'];
            $this->photo = $user['photo'];
            $this->name = $user['name'];

            return $this;
        }catch(PDOException $e){
            return false;
        }
    }

    public function delete($id){
        try{
            $query = $this->db->connect()->prepare('DELETE FROM users WHERE id = :id');
            $query->execute([ 'id' => $id]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public function update(){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET username = :username, password = :password, budget = :budget, photo = :photo, name = :name WHERE id = :id');
            $query->execute([
                'id'        => $this->id,
                'username' => $this->username, 
                'password' => $this->password,
                'budget' => $this->budget,
                'photo' => $this->photo,
                'name' => $this->name
                ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public function exists($username){
        try{
            $query = $this->db->connect()->prepare('SELECT username FROM users WHERE username = :username');
            $query->execute( ['username' => $this->username]);
            
            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public function from($array){
        $this->id = $array['id'];
        $this->username = $array['username'];
        $this->password = $array['password'];
        $this->role = $array['role'];
        $this->budget = $array['budget'];
        $this->photo = $array['photo'];
        $this->name = $array['name'];
    }

    private function getHashedPassword($password){
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }

    public function setUsername($username){ $this->username = $username;}
    public function setPassword($password, $hash = true){ 
        if($hash){
            $this->password = $this->getHashedPassword($password);
        }else{
            $this->password = $password;
        }
        
    }
    public function setId($id){         $this->id = $id;}
    public function setRole($role){         $this->role = $role;}
    public function setBudget($budget){     $this->budget = $budget;}
    public function setPhoto($photo){       $this->photo = $photo;}
    public function setName($name){         $this->name = $name;}

    public function getId(){ return $this->id;}
    public function getUsername(){ return $this->username;}
    public function getPassword(){ return $this->password;}
    public function getRole(){ return $this->role;}
    public function getBudget(){ return $this->budget;}
    public function getPhoto(){ return $this->photo;}
    public function getName(){ return $this->name;}
}

?>