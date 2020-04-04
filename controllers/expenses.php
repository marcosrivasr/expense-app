
<?php

class Expenses extends ControllerSession{

    function __construct(){
        parent::__construct();
    }

     function render(){
        $expenses = $this->getExpenses(5);
        $this->view->expenses = $expenses;
        $this->view->count = sizeof($expenses);
        $this->view->totalThisMonth = $this->getTotalAmountThisMonth();
        $this->view->username = $id_user  = $this->getUserSession()->getUserSessionData()['username'];;
        $this->view->budget = $this->getBudget();
        $this->getBudget();
        $this->view->render('dashboard/index');
    }

    private function newExpense(){
        if(!isset($_POST['title']) && !isset($_POST['amount']) && !isset($_POST['category']) ) header('location: /expense-app');

        if($this->getUserSession()->getUserSessionData() == NULL) header('location: /expense-app');

        $title    = $_POST['title'];
        $amount   = (float) $_POST['amount'];
        $category = $_POST['category'];
        $id_user  = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->insert($title, $amount, $category, $id_user);
    }

    private function deleteExpense(){
        if(!isset($_POST['id'])) header('location: /expense-app');

        $id_expense    = $_POST['id'];
        $id_user       = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->delete($id_expense, $id_user);
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
        $id_user    = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->modify($id_expense, $title, $amount, $category, $id_user);
    }

    private function getExpenses($n = 0){
        if($n < 0) return NULL;
        $id_user = $this->getUserSession()->getUserSessionData()['id'];

        return $this->model->get($id_user, $n);   
    }

    private function getTotalAmountThisMonth(){
        $id_user = $this->getUserSession()->getUserSessionData()['id'];
        $res = $this->model->getTotal($id_user);
        if(!$res || $res === NULL) return 0;
        if($res < 0) return 0;
        return $res;
    }

    private function getBudget(){
        $id_user = $this->getUserSession()->getUserSessionData()['id'];
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

    function saludo(){
        echo "<p>Ejecutaste el método Saludoss</p>";
    }
}

?>