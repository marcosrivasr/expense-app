<?php

class ExpensesModel extends Model implements IModel{

    private $id;
    private $title;
    private $amount;
    private $categoryid;
    private $date;
    private $userid;

    public function setId($id){ $this->id = $id; }
    public function setTitle($title){ $this->title = $title; }
    public function setAmount($amount){ $this->amount = $amount; }
    public function setCategoryId($categoryid){ $this->categoryid = $categoryid; }
    public function setDate($date){ $this->date = $date; }
    public function setUserId($userid){ $this->userid = $userid; }

    public function getId(){ return $this->id;}
    public function getTitle(){ return $this->title; }
    public function getAmount(){ return $this->amount; }
    public function getCategoryId(){ return $this->categoryid; }
    public function getDate(){ return $this->date; }
    public function getUserId(){ return $this->userid; }


    public function __construct(){
        parent::__construct();
    }

    public function save(){
        try{
            $query = $this->prepare('INSERT INTO expenses (title, amount, category_id, date, id_user) VALUES(:title, :amount, :category, :d, :user)');
            $query->execute([
                'title' => $this->title, 
                'amount' => $this->amount, 
                'category' => $this->categoryid, 
                'user' => $this->userid, 
                'd' => $this->date
            ]);
            if($query->rowCount()) return true;

            return false;
        }catch(PDOException $e){
            return false;
        }
    }
    public function getAll(){
        $items = [];

        try{
            $query = $this->query('SELECT * FROM expenses');

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ExpensesModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            echo $e;
        }
    }
    
    public function get($id){
        try{
            $query = $this->prepare('SELECT * FROM expenses WHERE id = :id');
            $query->execute([ 'id' => $id]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            $this->from($user);

            return $this;
        }catch(PDOException $e){
            return false;
        }
    }

    public function getAllByUserId($userid){
        $items = [];

        try{
            $query = $this->prepare('SELECT * FROM expenses WHERE id_user = :userid');
            $query->execute([ "userid" => $userid]);

            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ExpensesModel();
                $item->from($p); 
                
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            echo $e;
        }
    }

    public function getByUserIdAndLimit($userid, $n){
        $items = [];
        try{
            $query = $this->prepare('SELECT * FROM expenses WHERE id_user = :userid ORDER BY expenses.date DESC LIMIT 0, :n ');
            $query->execute([ 'n' => $n, 'userid' => $userid]);
            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new ExpensesModel();
                $item->from($p); 
                
                array_push($items, $item);
            }
            error_log("ExpensesModel::getByUserIdAndLimit(): count: " . count($items));
            return $items;
        }catch(PDOException $e){
            return false;
        }
    }
    /**
     * Regresa el monto total de expenses en este mes
     */
    function getTotalThisMonth($iduser){
        try{
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total FROM expenses WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :iduser');
            $query->execute(['year' => $year, 'month' => $month, 'iduser' => $iduser]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }
    /**
     * Obtiene el número de transacciones por mes
     */
    function getTotalExpensesThisMonth($iduser){
        try{
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT COUNT(id) AS total FROM expenses WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :iduser');
            $query->execute(['year' => $year, 'month' => $month, 'iduser' => $iduser]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
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
            $query = $this->db->connect()->prepare('UPDATE expenses SET title = :title, amount = :amount, category_id = :category, date = :d, id_user = :user WHERE id = :id');
            $query->execute([
                'title' => $this->title, 
                'amount' => $this->amount, 
                'category' => $this->categoryid, 
                'user' => $this->userid, 
                'd' => $this->date
            ]);
            return true;
        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    public function from($array){
        $this->id = $array['id'];
        $this->title = $array['title'];
        $this->amount = $array['amount'];
        $this->categoryid = $array['category_id'];
        $this->date = $array['date'];
        $this->userid = $array['id_user'];
    }




    function insert($title, $amount, $category, $date, $id_user){
        try{
            $query = $this->db->connect()->prepare('INSERT INTO expenses (title, amount, category_id, date, id_user) VALUES(:title, :amount, :category, :d, :user)');
            $query->execute(['title' => $title, 'amount' => $amount, 'category' => $category, 'user' => $id_user, 'd' => $date]);
            if($query->rowCount()) return true;
            return false;
        }catch(PDOException $e){
            return false;
        }
    }

//FIXME: confirmar para eliminar
    /* function delete($id_expense, $id_user){
        try{
            $query = $this->db->connect()->prepare('DELETE FROM expenses WHERE id = :id and id_user = :user');
            $query->execute(['id' => $id_expense,'user' => $id_user]);
            if($query->rowCount()) return true;
            return false;
        }catch(PDOException $e){
            //echo $e->getMessage();
            //echo "Ya existe esa matrícula";
            return false;
        }
    } */

    function modify($id_expense, $title, $amount, $category, $id_user){
        // TODO: completar funcion
    }

    //FIXME: confirmar para eliminar
    function get2($user, $n){
        $res = [];
        $items = [];
        try{
            if($n === 0){
                $query = $this->db->connect()->prepare('SELECT categories.id as "category.id", categories.color as "category.color", expenses.id as "expense.id", categories.name, expenses.title, expenses.amount, expenses.date FROM expenses INNER JOIN categories WHERE categories.id = expenses.category_id AND id_user = :user ORDER BY expenses.date DESC ');
                $query->execute(['user' => $user]);
            }else{
                $query = $this->db->connect()->prepare('SELECT categories.id as "category.id", categories.color as "category.color", expenses.id as "expense.id", categories.name, expenses.title, expenses.amount, expenses.date FROM expenses INNER JOIN categories WHERE categories.id = expenses.category_id AND id_user = :user ORDER BY expenses.date DESC LIMIT 0, :n ');
                $query->execute(['user' => $user, 'n' => $n]);
            }
        }catch(PDOException $e){
            return NULL;
        }

        if($query->rowCount() == 0) return array();
        
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $item = Array(
                'expense_id' => $row['expense.id'],
                'category_name' => $row['name'],
                'category_id' => $row['category.id'],
                'expense_title' => $row['title'],
                'amount' =>  $row['amount'],
                'date' => $row['date'],
                'category_color' => $row['category.color'],
            );
            array_push($items, $item);
        }
        
        return $items;  
    }

    function getTotal($user){
        try{
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total FROM expenses WHERE YEAR(date) = :year AND MONTH(date) = :month AND id_user = :user');
            $query->execute(['year' => $year, 'month' => $month, 'user' => $user]);

            $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            if($total == NULL) $total = 0;
            
            return $total;

        }catch(PDOException $e){
            return NULL;
        }
    }
//FIXME: confirmar para MOVER
    function getTotalByCategory($cid, $user){
        try{
            $total = 0;
            $year = date('Y');
            $month = date('m');
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from expenses WHERE category_id = :val AND id_user = :user AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['val' => $cid, 'user' => $user, 'year' => $year, 'month' => $month]);

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
//FIXME: confirmar para MOVER
    function getTotalByMonthAndCategory($date, $category, $userid){
        try{
            $total = 0;
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 7);
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from expenses WHERE category_id = :val AND id_user = :user AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['val' => $category, 'user' => $userid, 'year' => $year, 'month' => $month]);

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
    }
}

?>