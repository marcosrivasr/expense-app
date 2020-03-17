
<?php

class Signup extends ControllerSession{

    function __construct(){
        parent::__construct();
        $userSession = new UserSessionInstance();
    }

    function render(){
        $this->view->render('login/signup');
    }

    function newUser(){
        if(isset($_POST['username']) && isset($_POST['password']) ){
            $username = $_POST['username'];
            $password = $_POST['password'];

            //validate data
            if($username == '' || empty($username) || $password == '' || empty($password)){
                // error al validar datos
                $this->errorAtSignup('Campos vacios');
                return;
            }
            $registerNewUser = $this->model->insert($username, $password);

            if($registerNewUser){
                $this->view->render('login/index');
            }else{
                //error al registrar, que intente de nuevo
                $this->errorAtSignup('Error al registrar el usuario. Elige un nombre de usuario diferente');
                return;
            }
        }else{
            // error, cargar vista con errores
            $this->errorAtSignup('Error al procesar solicitud');
        }
    }

    function errorAtSignup($err = ''){
        $this->view->errorMessage = $err;
        $this->view->render('login/signup');
    }

    function saludo(){
        echo "<p>Ejecutaste el método Saludo</p>";
    }
}

?>