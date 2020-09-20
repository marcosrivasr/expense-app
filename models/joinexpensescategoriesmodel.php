<?php

class JoinExpensesCategoriesModel extends Model{

    private $expenseId;
    private $title;
    private $amount;
    private $categoryId;
    private $date;
    private $userId;
    private $nameCategory;
    private $color;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAll($userid){
        $items = [];
        try{
            $query = $this->prepare('SELECT expenses.id as expense_id, title, category_id, amount, date, id_user, categories.id, name, color  FROM expenses INNER JOIN categories WHERE expenses.category_id = categories.id AND expenses.id_user = :userid ORDER BY date');
            $query->execute(["userid" => $userid]);


            while($p = $query->fetch(PDO::FETCH_ASSOC)){
                $item = new JoinExpensesCategoriesModel();
                $item->from($p);
                array_push($items, $item);
            }

            return $items;

        }catch(PDOException $e){
            echo $e;
        }
    }

    //FIXME: mover a ExpensesModel
    function getTotalByMonthAndCategory($date, $categoryid, $userid){
        try{
            $total = 0;
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 7);
            $query = $this->db->connect()->prepare('SELECT SUM(amount) AS total from expenses WHERE category_id = :val AND id_user = :user AND YEAR(date) = :year AND MONTH(date) = :month');
            $query->execute(['val' => $categoryid, 'user' => $userid, 'year' => $year, 'month' => $month]);

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

    

    public function from($array){
        $this->expenseId = $array['expense_id'];
        $this->title = $array['title'];
        $this->categoryId = $array['category_id'];
        $this->amount = $array['amount'];
        $this->date = $array['date'];
        $this->userId = $array['id_user'];
        $this->nameCategory = $array['name'];
        $this->color = $array['color'];
    }

    public function toArray(){
        $array = [];
        $array['id'] = $this->expenseId;
        $array['title'] = $this->title;
        $array['category_id'] = $this->categoryId;
        $array['amount'] = $this->amount;
        $array['date'] = $this->date;
        $array['id_user'] = $this->userId;
        $array['name'] = $this->nameCategory;
        $array['color'] = $this->color;

        return $array;
    }

    public function getExpenseId(){return $this->expenseId;}
    public function getTitle(){return $this->title;}
    public function getCategoryId(){return $this->categoryId;}
    public function getAmount(){return $this->amount;}
    public function getDate(){return $this->date;}
    public function getUserId(){return $this->userId;}
    public function getNameCategory(){return $this->nameCategory;}
    public function getColor(){return $this->color;}
}
?>