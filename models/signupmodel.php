<?php

class SignupModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function insert($username, $password){
        // insertar datos en la BD
        try{
            $query = $this->db->connect()->prepare('INSERT INTO USERS (USERNAME, PASSWORD, ROLE) VALUES(:username, :password, "user")');
            $query->execute(['username' => $username, 'password' => $password]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage();
            //echo "Ya existe esa matrícula";
            return false;
        }
        
    }
}

?>