
<?php

class Expenses extends ControllerSession{

    function __construct(){
        parent::__construct();
    }

    function render(){
        $this->view->render('dashboard/index');
    }

    function newExpense(){
        if(!isset($_POST['title']) && !isset($_POST['amount']) && !isset($_POST['category']) ) header('location: /expense-app');

        if($this->getUserSession()->getUserSessionData() == NULL) header('location: /expense-app');

        $title    = $_POST['title'];
        $amount   = (float) $_POST['amount'];
        $category = $_POST['category'];
        $id_user  = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->insert($title, $amount, $category, $id_user);
    }

    function deleteExpense(){
        if(!isset($_POST['id'])) header('location: /expense-app');

        $id_expense    = $_POST['id'];
        $id_user       = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->delete($id_expense, $id_user);
    }

    function modifyExpense(){
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

    function getExpenses($n = 0){
        if($n < 0) return NULL;
        $id_user = $this->getUserSession()->getUserSessionData()['id'];

        $this->model->get($id_user, $n);
    }



    

    function saludo(){
        echo "<p>Ejecutaste el método Saludoss</p>";
    }
}

?>