<?php

class SignupModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function insert($username, $password){
        // insertar datos en la BD
        try{
            $query = $this->db->connect()->prepare('INSERT INTO users (username, password, role, budget, photo, name) VALUES(:username, :password, "user", 0.0, "","" )');
            $query->execute(['username' => $username, 'password' => $password]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
        
    }
}

?>