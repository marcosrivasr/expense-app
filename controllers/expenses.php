
<?php

class Expenses extends SessionController{

    private $expenses;
    private $error = NULL;
    private $count;
    private $totalThisMonth;

    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Expenses::constructor() ");
    }

     function render(){
        error_log("Expenses::RENDER() ");
        $expenses       = $this->getExpenses(5);
        $totalThisMonth = $this->getTotalAmountThisMonth();
        $categories     = $this->getCategories();
        
        $this->view->user           = $this->user;
        //$this->view->expenses       = $expenses;
        //$this->view->totalThisMonth = $totalThisMonth;
        $this->view->categories     = $categories;

        $this->view->render('dashboard/index', [
            'user' => $this->user,
            'expenses' => $expenses,
            'totalThisMonth' => $totalThisMonth
            
        ]);
    }
    
    //obtiene la lista de expenses y $n tiene el número de expenses por transacción
    private function getExpenses($n = 0){
        if($n < 0) return NULL;
        error_log("Expenses::getExpenses() id = " . $this->getUserSessionData()->getId());
        return $this->model->getByUserIdAndLimit($this->getUserSessionData()->getId(), $n);   
    }

    function newExpense(){
        if(!isset($_POST['title']) || !isset($_POST['amount']) || !isset($_POST['category']) || !isset($_POST['date']) ){
            header('location: ../');
            return;
        }

        if($this->getUserId() == NULL){
            header('location: ../');
            return;
        }

        $title    = $_POST['title'];
        $amount   = (float) $_POST['amount'];
        $category = $_POST['category'];
        $date     = $_POST['date'];
        $id_user  = $this->getUserId();

        if( empty($title) || empty($amount) || empty($category) || empty($date) ){
            header('location: ../');
            return;
        }

        $this->model->insert($title, $amount, $category, $date, $id_user);

        header('location: ../');
    }

    private function getTotalAmountThisMonth(){
        $res = $this->model->getTotal($this->getUserId());

        return $res;
    }

    private function getBudget(){
        include_once 'models/usermodel.php';
        $userModel = new UserModel();

        return $userModel->getBudget($this->getUserId());
    }

    // new expense UI
    function create(){
        $categories = new CategoriesModel();
        $this->view->render('dashboard/create', [
            "categories" => $categories->getAll(),
            "user" => $this->user
        ]);
    } 

    function getCategories(){
        include_once 'models/categoriesmodel.php';
        $categoriesModel = new CategoriesModel();

        $categories = $categoriesModel->get();
        $id_user    = $this->getUserId();
        $res        = [];

        if($categories === NULL) return NULL;

        foreach ($categories as $cat) {
            $total = $this->model->getTotalByCategory($cat['id'], $id_user);
            if($total === NULL) $total = 0;
            $item = Array(
                'id' => $cat['id'],
                'name' => $cat['name'],
                'total' => $total
            );
            array_push($res, $item);
        }

        return $res;
    }

    function getCategoriesId(){
        include_once 'models/categoriesmodel.php';
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->get();

        $res = [];
        foreach ($categories as $cat) {
            array_push($res, $cat['id']);
        }

        return $res;
    }

    function getUser(){
        include_once 'models/usermodel.php';
        $userModel = new UserModel();
        $userid = $this->getUserId();
        $username = $this->getUsername();
        $name = $userModel->getName($userid);
        $photo = $userModel->getPhoto($userid);
        $budget = $userModel->getBudget($userid);

        if($name === NULL || empty($username)) $name = $username;
        if($photo === NULL || empty($photo)) $photo = '';
        if($budget === NULL || empty($budget) || $budget < 0) $budget = 0.0;

        return Array(
            'username' => $username,
            'name' => $name,
            'photo' => $photo,
            'budget' => $budget
        );
    }

    function history($params = NULL){

        $this->view->user       = $this->getUser();
        $this->view->history    = $this->getHistory();
        $this->view->dates      = $this->getDateList();
        $this->view->categories = $this->getCategoryList();

        $this->view->render('dashboard/history');
    }

    private function getHistory(){
        return $this->getExpenses(0);
    }

    // crea una lista con los meses donde hay expenses
    private function getDateList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            array_push($res, substr($item['date'],0, 7 ));
        }
        $res = array_unique($res);
        return $res;
    }

    // crea una lista con las categorias donde hay expenses
    private function getCategoryList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            array_push($res, $item['category_name']);
        }
        $res = array_unique($res);
        return $res;
    }

    // crea una lista con los colores dependiendo de las categorias
    private function getCategoryColorList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            array_push($res, $item['category_color']);
        }
        $res = array_unique($res);
        return $res;
    }

    // devuelve el JSON para las llamadas AJAX
    function getHistoryJSON(){
        $expenses = $this->getExpenses(0); //modificar para hacer paginacion
        foreach ($expenses as $key => $ex) {
            $expenses[$key]['amount'] =number_format($expenses[$key]['amount'], 2);
        }
        header('Content-Type: application/json');
        echo json_encode($expenses);

    }

    function getExpensesJSON(){
        $res = [];
        $categories     = $this->getCategoriesId();
        $categoryNames  = $this->getCategoryList();
        $categoryColors = $this->getCategoryColorList();

        array_unshift($categoryNames, 'categorias');
        array_unshift($categoryColors, NULL);

        $categories     = array_values($categories);
        $categoryNames  = array_values($categoryNames);
        $categorycolors = array_values($categoryColors);

        $months = array_values($this->getDateList());

        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($categories); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $categories[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }
        array_unshift($res, $categoryNames);
        array_unshift($res, $categoryColors);
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    function getTotalByMonthAndCategory($date, $category){
        $id_user = $this->getUserId();
        

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 7);

        $total = $this->model->getTotalByMonthAndCategory($date, $category, $id_user);
        if($total == NULL) $total = 0;
        return $total;
    }

    function delete($params){
        if($params === NULL) header('location: ' . constant('URL') . 'expenses/history?message=failure');

        $id_user = $this->getUserId();
        $res = $this->model->delete($params[0], $id_user);
        if($res){
            header('location: ' . constant('URL') . 'expenses/history?message=success');
        }else{
            header('location: ' . constant('URL') . 'expenses/history?message=failure');
        }
    }

    function test(){
        var_dump($this->model->getAll());
    }


}

?>