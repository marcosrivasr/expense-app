<?php

class UserModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    function getBudget($id){
        try{
            $query = $this->db->connect()->prepare('SELECT budget FROM users WHERE id = :id');
            $query->execute(['id' => $id]);

            if($query->rowCount() > 0){
                $total = $query->fetch(PDO::FETCH_ASSOC)['budget'];
            }else{
                return 0;
            }
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }

    function getName($id){
        try{
            $query = $this->db->connect()->prepare('SELECT name FROM users WHERE id = :id');
            $query->execute(['id' => $id]);

            if($query->rowCount() > 0){
                return $query->fetch(PDO::FETCH_ASSOC)['name'];
            }else{
                return NULL;
            }

        }catch(PDOException $e){
            return NULL;
        }
    }

    function getPhoto($id){
        try{
            $query = $this->db->connect()->prepare('SELECT photo FROM users WHERE id = :id');
            $query->execute(['id' => $id]);

            if($query->rowCount() > 0){
                return $query->fetch(PDO::FETCH_ASSOC)['photo'];
            }else{
                return NULL;
            }

        }catch(PDOException $e){
            return NULL;
        }
    }

    function updateBudget($budget, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET budget = :val WHERE id = :id');
            $query->execute(['val' => $budget, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function updateName($name, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET name = :val WHERE id = :id');
            $query->execute(['val' => $name, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function updatePhoto($name, $iduser){
        try{
            $query = $this->db->connect()->prepare('UPDATE users SET photo = :val WHERE id = :id');
            $query->execute(['val' => $name, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function updatePassword($new, $iduser){
        try{
            $hash = password_hash($new, PASSWORD_DEFAULT, ['cost' => 10]);
            $query = $this->db->connect()->prepare('UPDATE users SET password = :val WHERE id = :id');
            $query->execute(['val' => $hash, 'id' => $iduser]);

            if($query->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        
        }catch(PDOException $e){
            return NULL;
        }
    }

    function comparePasswords($current, $userid){
        try{
            $query = $this->db->connect()->prepare('SELECT id, password FROM USERS WHERE id = :id');
            $query->execute(['id' => $userid]);
            
            if($row = $query->fetch(PDO::FETCH_ASSOC)) return password_verify($current, $row['password']);

            return false;
        }catch(PDOException $e){
            return false;
        }
    }
}

?>