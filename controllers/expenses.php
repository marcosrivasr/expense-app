
<?php

class Expenses extends SessionController{


    private $user;

    function __construct(){
        parent::__construct();

        $this->user = $this->getUserSessionData();
        error_log("Expenses::constructor() ");
    }

     function render(){
        error_log("Expenses::RENDER() ");
        $expenses               = $this->getExpenses(5);
        $totalThisMonth         = $this->model->getTotalAmountThisMonth($this->user->getId());
        $maxExpensesThisMonth = $this->model->getMaxExpensesThisMonth($this->user->getId());
        $categories             = $this->getCategories();
        
        $this->view->categories     = $categories;

        $this->view->render('dashboard/index', [
            'user' => $this->user,
            'expenses' => $expenses,
            'totalAmountThisMonth' => $totalThisMonth,
            'maxExpensesThisMonth' => $maxExpensesThisMonth,
            'categories' => $categories
        ]);
    }
    
    //obtiene la lista de expenses y $n tiene el número de expenses por transacción
    private function getExpenses($n = 0){
        if($n < 0) return NULL;
        error_log("Expenses::getExpenses() id = " . $this->getUserSessionData()->getId());
        return $this->model->getByUserIdAndLimit($this->getUserSessionData()->getId(), $n);   
    }

    function newExpense(){
        error_log('Expenses::newExpense()');
        if(!$this->existPOST(['title', 'amount', 'category', 'date'])){
            //header('location: ../');
            $this->redirect('expenses', ['Error' => Errors::ERROR_EXPENSES_NEWEXPENSE_EMPTY]);
            return;
        }

        if($this->user == NULL){
            //header('location: ../');
            $this->redirect('expenses', ['Error' => Errors::ERROR_EXPENSES_NEWEXPENSE]);
            return;
        }

        $expense = new ExpensesModel();

        $expense->setTitle($this->getPost('title'));
        $expense->setAmount((float)$this->getPost('amount'));
        $expense->setCategoryId($this->getPost('category'));
        $expense->setDate($this->getPost('date'));
        $expense->setUserId($this->user->getId());

        $expense->save();

        //header('location: ../');
        $this->redirect('expenses', ['success' => Success::SUCCESS_EXPENSES_NEWEXPENSE]);
    }

/*     private function getBudget(){
        include_once 'models/usermodel.php';
        $userModel = new UserModel();

        return $userModel->getBudget($this->getUserId());
    } */

    // new expense UI
    function create(){
        $categories = new CategoriesModel();
        $this->view->render('dashboard/create', [
            "categories" => $categories->getAll(),
            "user" => $this->user
        ]);
    } 

    function getCategories(){
        $res = [];
        $categoriesModel = new CategoriesModel();
        $expensesModel = new ExpensesModel();

        $categories = $categoriesModel->getAll();

        foreach ($categories as $category) {
            $categoryArray = [];
            //obtenemos la suma de amount de expenses por categoria
            $total = $expensesModel->getTotalByCategoryThisMonth($category->getId(), $this->user->getId());
            // obtenemos el número de expenses por categoria por mes
            $numberOfExpenses = $expensesModel->getNumberOfExpensesByCategoryThisMonth($category->getId(), $this->user->getId());
            
            if($numberOfExpenses > 0){
                $categoryArray['total'] = $total;
                $categoryArray['count'] = $numberOfExpenses;
                $categoryArray['category'] = $category;
                array_push($res, $categoryArray);
            }
            
        }
        return $res;
    }

    function getCategoryIds(){
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $categories = $joinExpensesCategoriesModel->getAll($this->user->getId());

        $res = [];
        foreach ($categories as $cat) {
            array_push($res, $cat->getCategoryId());
        }
        $res = array_values(array_unique($res));
        //var_dump($res);
        return $res;
    }

    // crea una lista con los meses donde hay expenses
    private function getDateList(){
        $months = [];
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($months, substr($expense->getDate(),0, 7 ));
        }
        $months = array_values(array_unique($months));
        //mostrar los últimos 3 meses
        if(count($months) >3){
            array_push($res, array_pop($months));
            array_push($res, array_pop($months));
            array_push($res, array_pop($months));
        }
        return $res;
    }

    // crea una lista con las categorias donde hay expenses
    private function getCategoryList(){
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($res, $expense->getNameCategory());
        }
        $res = array_values(array_unique($res));
        //var_dump($res);
        return $res;
    }

    // crea una lista con los colores dependiendo de las categorias
    private function getCategoryColorList(){
        $res = [];
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            array_push($res, $expense->getColor());
        }
        $res = array_unique($res);
        $res = array_values(array_unique($res));
        //var_dump($res);
        return $res;
    }
/*
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
    */

    function history($params = NULL){
        $this->view->dates      = $this->getDateList();
        $this->view->categories = $this->getCategoryList();

        $this->view->render('dashboard/history',[
            "user" => $this->user,
            "expenses" => $this->getHistory()
        ]);
    }

    private function getHistory(){
        $joinExpensesCategories = new JoinExpensesCategoriesModel();
        return $joinExpensesCategories->getAll($this->user->getId());
    }

    

    // devuelve el JSON para las llamadas AJAX
    function getHistoryJSON(){
        header('Content-Type: application/json');
        $res = [];
        $joinExpensesCategories = new JoinExpensesCategoriesModel();
        $expenses = $joinExpensesCategories->getAll($this->user->getId());

        foreach ($expenses as $expense) {
            //$expenses[$key]['amount'] =number_format($expenses[$key]['amount'], 2);
            array_push($res, $expense->toArray());
        }
        
        echo json_encode($res);

    }

    function getExpensesJSON(){
        header('Content-Type: application/json');

        $res = [];
        $categoryIds     = $this->getCategoryIds();
        $categoryNames  = $this->getCategoryList();
        $categoryColors = $this->getCategoryColorList();

        array_unshift($categoryNames, 'mes');
        array_unshift($categoryColors, 'categorias');
        /* array_unshift($categoryNames, 'categorias');
        array_unshift($categoryColors, NULL); */

        $months = $this->getDateList();

        for($i = 0; $i < count($months); $i++){
            $item = array($months[$i]);
            for($j = 0; $j < count($categoryIds); $j++){
                $total = $this->getTotalByMonthAndCategory( $months[$i], $categoryIds[$j]);
                array_push( $item, $total );
            }   
            array_push($res, $item);
        }

        array_unshift($res, $categoryNames);
        array_unshift($res, $categoryColors);
        
        echo json_encode($res);
    }

    function getTotalByMonthAndCategory($date, $categoryid){
        $iduser = $this->user->getId();
        $joinExpensesCategoriesModel = new JoinExpensesCategoriesModel();
        //$expenses = $joinExpensesCategoriesModel->getAll($this->user->getId());

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 7);

        $total = $joinExpensesCategoriesModel->getTotalByMonthAndCategory($date, $categoryid, $iduser);
        if($total == NULL) $total = 0;
        return $total;
    }

    function delete($params){
        error_log("Expenses::delete()");
        
        if($params === NULL) $this->redirect('expenses/history', ['error' => Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS]);//header('location: ' . constant('URL') . 'expenses/history?message=failure');
        $id = $params[0];
        error_log("Expenses::delete() id = " . $id);
        $res = $this->model->delete($id);
        if($res){
            $this->redirect('expenses/history', ['success' => Success::SUCCESS_EXPENSES_DELETE]);
            //header('location: ' . constant('URL') . 'expenses/history?message=success');
        }else{
            $this->redirect('expenses/history', ['error' => Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS]);
            header('location: ' . constant('URL') . 'expenses/history?message=failure');
        }
    }

    function test(){
        var_dump($this->getHistory());
    }


}

?>