<?php

class LoginModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    public function login($username, $password){
        // insertar datos en la BD
        try{
            //$query = $this->db->connect()->prepare('SELECT id, username, password, role FROM USERS WHERE USERNAME = :username AND PASSWORD = :password');
            $query = $this->db->connect()->prepare('SELECT id, username, password, role FROM USERS WHERE USERNAME = :username');
            //$query->execute(['username' => $username, 'password' => $password]);
            $query->execute(['username' => $username]);
            
            if($row = $query->fetch(PDO::FETCH_ASSOC)){
                echo $password;
                var_dump(password_verify($password, $row['password']));
                if(password_verify($password, $row['password'])){
                    return ['id' => $row['id'], 'username' => $row['username'], 'role' => $row['role']];
                }else{
                    return NULL;
                }
                
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