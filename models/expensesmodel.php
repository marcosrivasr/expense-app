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

            $query = $this->db->connect()->prepare('SELECT categories.id as "category.id", expenses.id as "expense.id", categories.name, expenses.title, expenses.amount, expenses.date FROM expenses INNER JOIN categories WHERE categories.id = expenses.category_id AND id_user = :user LIMIT 0, :n');
            $query->execute(['user' => $user, 'n' => $n]);

            $res = [];
            if($query->rowCount() > 0){
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $item = Array(
                        'expense_id' => $row['expense.id'],
                        'category_name' => $row['name'],
                        'category_id' => $row['category.id'],
                        'expense_title' => $row['title'],
                        'amount' => $row['amount'],
                        'date' => $row['date'],
                    );
                    array_push($res, $item);
                }
            }else{
                return 0;
            }
            
            return $res;

        }catch(PDOException $e){
            return NULL;
        }
    }

    function getTotal($user){
        try{
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total FROM expenses WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :user');
            $query->execute(['year' => $year, 'month' => $month, 'user' => $user]);

            if($query->rowCount() > 0){
                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            }else{
                return 0;
            }
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }

    }

    function getField($user, $name ){
        /* try{
            $query = $this->db->connect()->prepare('SELECT :field FROM expenses WHERE');
            $query->execute(['year' => $year, 'month' => $month]);

            if($query->rowCount() > 0){
                $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            }else{
                return 0;
            }
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        } */
    }
}

?>