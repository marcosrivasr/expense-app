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
}

?>