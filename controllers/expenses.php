
<?php

class Expenses extends ControllerSession{

    private $expenses;
    private $error = NULL;
    private $count;
    private $totalThisMonth;

    function __construct(){
        parent::__construct();
    }

     function render(){
        $expenses       = $this->getExpenses(5);
        $totalThisMonth = $this->getTotalAmountThisMonth();
        $categories     = $this->getCategories();
        
        
        $this->view->expenses = $expenses;
        $this->view->totalThisMonth = $totalThisMonth;
        $this->view->user = $this->getUser();
        $this->view->categories = $categories;
        

        $this->view->render('dashboard/index');
    }

    function newExpense(){
        if(!isset($_POST['title']) || !isset($_POST['amount']) || !isset($_POST['category']) || !isset($_POST['date']) ){
            header('location: ../');
            return;
        }

        if($this->getUserSession()->getUserSessionData() == NULL){
            header('location: ../');
            return;
        }

        $title    = $_POST['title'];
        $amount   = (float) $_POST['amount'];
        $category = $_POST['category'];
        $date = $_POST['date'];
        $id_user  = $this->getUserId();

        if( empty($title) || empty($amount) || empty($category) || empty($date) ){
            header('location: create');
            return;
        }

        $this->model->insert($title, $amount, $category, $date, $id_user);

        header('location: ../');
    }

    private function modifyExpense(){
        if(!isset($_POST['title']) && 
            !isset($_POST['amount']) && 
            !isset($_POST['category']) &&
            !isset($_POST['id']) ) header('location: /expense-app');

        $id_expense = $_POST['id'];
        $title      = $_POST['title'];
        $amount     = (float) $_POST['amount'];
        $category   = $_POST['category'];
        $id_user    = $this->getUserId();

        $this->model->modify($id_expense, $title, $amount, $category, $id_user);
    }

    private function getExpenses($n = 0){
        if($n < 0) return NULL;
        
        return $this->model->get($this->getUserId(), $n);   
    }

    private function getTotalAmountThisMonth(){
        $id_user = $this->getUserId();
        $res = $this->model->getTotal($id_user);

        return $res;
    }

    private function getBudget(){
        $id_user = $this->getUserId();
        include_once 'models/usermodel.php';
        $userController = new UserModel();
        return $userController->getBudget($id_user);
    }

    function create(){
        include_once 'models/categoriesmodel.php';
        $categoriesModel = new CategoriesModel();
        $this->view->categories = $categoriesModel->get();
        $this->view->user = $this->getUser();
        $this->view->render('dashboard/create');
    }

    function getCategories(){
        include_once 'models/categoriesmodel.php';
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->get();
        $id_user = $this->getUserId();
        $res = [];
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

        if($params != NULL){
            
        }
        $this->view->user = $this->getUser();
        $this->view->history = $this->getHistory();
        $this->view->dates   = $this->getDateList();
        $this->view->categories = $this->getCategoryList();
        $this->view->render('dashboard/history');
    }

    private function getHistory(){
        return $this->getExpenses(0);
    }

    private function getDateList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            array_push($res, substr($item['date'],0, 7 ));
        }
        $res = array_unique($res);
        return $res;
    }
    private function getCategoryList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            //array_push($res, array($item['category_name'], $item['category_color']));
            array_push($res, $item['category_name']);
        }
        $res = array_unique($res);
        return $res;
    }
    private function getCategoryColorList(){
        $arr = $this->getExpenses(0);
        $res = [];
        foreach ($arr as $item) {
            //array_push($res, array($item['category_name'], $item['category_color']));
            array_push($res, $item['category_color']);
        }
        $res = array_unique($res);
        return $res;
    }

    function getHistoryJSON(){
        $expenses = $this->getExpenses(0); //modificar para hacer paginacion
        foreach ($expenses as $key =>$ex) {
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
        $id_user = $this->getUserId();
        //$res = $this->model->delete($params[0], $id_user);
        $res = $this->model->delete(-2, $id_user);
        if($res){
            header('location: ' . constant('URL') . 'expenses/history/success/sdsd');
        }else{
            header('location: ' . constant('URL') . 'expenses/history/failure/sdsd');
            
        }
    }


}

?>