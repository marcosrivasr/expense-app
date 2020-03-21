<?php

class ExpensesModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    function insert($title, $amount, $category, $id_user){
        try{
            $query = $this->db->connect()->prepare('INSERT INTO EXPENSES (TITLE, AMOUNT, CATEGORY_ID, DATE, ID_USER) VALUES(:title, :amount, :category, now(), :user)');
            $query->execute(['title' => $title, 'amount' => $amount, 'category' => $category, 'user' => $id_user]);
            return true;
        }catch(PDOException $e){
            //echo $e->getMessage();
            //echo "Ya existe esa matrícula";
            return false;
        }
    }

    function delete($id_expense, $id_user){
        // TODO: completar funcion
    }

    function modify($id_expense, $title, $amount, $category, $id_user){
        // TODO: completar funcion
    }

    function get($user, $n){
        try{
            $query = $this->db->connect()->prepare('SELECT `c`.`name`,`e`.`id`,`e`.`title`, `e`.`amount`,`e`.`date` FROM `expenses` AS `e` INNER JOIN `categories` AS `c` WHERE `c`.`id` = `e`.`category_id` AND e.user_id = :user LIMIT 0,:n');
            $res = $query->execute(['user' => $user, 'n' => $n]);
            if($res) return true;
        }catch(PDOException $e){
            return false;
        }
    }
}

?>