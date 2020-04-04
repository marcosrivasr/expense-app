
<?php

class CategoriesModel extends Model{

    public function __construct(){
        parent::__construct();
    }

    function get(){
        $res = [];
        try{
            $query = $this->db->connect()->query('SELECT * FROM categories');

            if($query->rowCount() > 0){
                
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $item = Array(
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'color' => $row['color'],
                    );
                    array_push($res, $item);
                }

                return $res;
            }else{
                return NULL;
            }
        }catch(PDOException $e){
            return NULL;
        }
    }
}

?>