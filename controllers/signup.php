
<?php

class Signup extends Controller{

    function __construct(){
        parent::__construct();
    }

    function render(){
        $this->view->render('login/signup');
    }

    function newUser(){
        if(isset($_POST['username']) && isset($_POST['password']) ){
            $username = $_POST['username'];
            $password = $_POST['password'];
            
        }else{
            // error, cargar vista con errores
        }
    }

    function error(){

    }

    function saludo(){
        echo "<p>Ejecutaste el método Saludo</p>";
    }
}

?>