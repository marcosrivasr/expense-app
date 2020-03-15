<?php

class LoginModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function login($username, $password){
        // insertar datos en la BD
        try{
            $query = $this->db->connect()->prepare('SELECT id, username, password, role FROM USERS WHERE USERNAME = :username AND PASSWORD = :password');
            $query->execute(['username' => $username, 'password' => $password]);
            
            if($row = $query->fetch(PDO::FETCH_ASSOC)){
                return ['id' => $row['id'], 'username' => $row['username'], 'role' => $row['role']];
            }else{
                return NULL;
            }
        }catch(PDOException $e){
            //echo $e->getMessage();
            //echo "Ya existe esa matrícula";
            return NULL;
        }
        
    }
}

?>