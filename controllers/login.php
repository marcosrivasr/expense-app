
<?php

class Login extends ControllerSession{

    function __construct(){
        parent::__construct();
    }

    function render(){
        $actual_link = trim("$_SERVER[REQUEST_URI]");
        $url = explode('/', $actual_link);
        $this->view->errorMessage = '';
        $this->view->render('login/index');
    }

    function authenticate(){
        if(isset($_POST['username']) && isset($_POST['password']) ){
            $username = $_POST['username'];
            $password = $_POST['password'];

            //validate data
            if($username == '' || 
                empty($username) || 
                $password == '' || 
                empty($password)){
                // error al validar datos
                $this->errorAtLogin('Campos vacios');
                return;
            }

            $loginUser = $this->model->login($username, $password);

            if($loginUser != NULL){
                $this->getUserSession()->initialize($loginUser);
            }else{
                //error al registrar, que intente de nuevo
                $this->errorAtLogin('Nombre de usuario y/o password incorrecto');
                return;
            }
        }else{
            // error, cargar vista con errores
            $this->errorAtLogin('Error al procesar solicitud');
        }
    }

    function errorAtLogin($err = ''){
        $this->view->errorMessage = $err;
        $this->view->render('login/index');
    }

    function saludo(){
        
    }
}

?>