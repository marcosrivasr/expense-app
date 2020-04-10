
<?php

class Expenses extends ControllerSession{

    function __construct(){
        parent::__construct();
    }

     function render(){
        $this->view->expenses = $this->getExpenses(5);
        if(!empty($this->view->expenses)){
            $this->view->count = sizeof($this->view->expenses);
        }else{
            $this->view->count = 0;
        }
        
        $this->view->totalThisMonth = $this->getTotalAmountThisMonth();
        $this->view->user = $this->getUser();
        $this->view->categories = $this->getCategories();

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
        $id_user = $this->getUserId();

        return $this->model->get($id_user, $n);   
    }

    private function getTotalAmountThisMonth(){
        $id_user = $this->getUserId();
        $res = $this->model->getTotal($id_user);
        if(!$res || $res === NULL) return 0;
        if($res < 0) return 0;
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
        $id_user = $this->getUserId();
        $res = [];
        foreach ($categories as $cat) {
            array_push($res, $cat['id']);
        }

        return $res;
    }

    function getUser(){
        include_once 'models/usermodel.php';
        $userModel = new UserModel();
        //$userid = $this->getUserSession()->getUserSessionData()['id'];
        $userid = $this->getUserId();
        //$username = $this->getUserSession()->getUserSessionData()['username'];
        $username = $this->getUsername();
        $name = $userModel->getName($userid);
        $photo = $userModel->getPhoto($userid);
        $budget = $userModel->getBudget($userid);

        if($name === NULL || empty($username)) $name = $username;
        if($photo === NULL || empty($photo)) $photo = 'default.png';
        if($budget === NULL || empty($budget) || $budget < 0) $budget = 0.0;

        return Array(
            'username' => $username,
            'name' => $name,
            'photo' => $photo,
            'budget' => $budget
        );
    }

    function history(){
        $this->view->history = $this->getHistory();
        $this->view->dates = $this->getDateList();
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
            array_push($res, $item['category_name']);
        }
        $res = array_unique($res);
        return $res;
    }

    function getHistoryJSON(){
        echo json_encode($this->getExpenses(0));
    }

    function getExpensesJSON(){
        $res = [];
        $categories = $this->getCategoriesId();
        $categoryNames = $this->getCategoryList();
        array_unshift($categoryNames, 'categorias');
        $categories = array_values($categories);
        $categoryNames = array_values($categoryNames);

        $months = array_values($this->getDateList());

        //var_dump($categories);

        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            //echo $months[$i] . '<br />';
            for($j = 0; $j < count($categories); $j++){
                //array_push( $item, getTotalByMonthAndCategory($months[$i], $categories[$j]) );
                //echo $categories[$j].'<br/>';
                $total = $this->getTotalByMonthAndCategory( $months[$i], $categories[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }
        array_unshift($res, $categoryNames);
        header('Content-Type: application/json');
        echo json_encode($res);

        //echo json_encode($legends);
        //echo json_encode($months);
    }

    function getTotalByMonthAndCategory($date, $category){
        $id_user = $this->getUserId();
        

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 7);

        $total = $this->model->getTotalByMonthAndCategory($date, $category, $id_user);
        if($total == NULL) $total = 0;
        return $total;
    }
}

?>