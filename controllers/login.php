
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
        if( $this->existPOST(['username', 'password']) ){
            $username = $this->getPost('username');
            $password = $this->getPost('password');

            //validate data
            if($username == '' || empty($username) || $password == '' || empty($password)){
                $this->errorAtLogin('Campos vacios');
                return;
            }
            // si el login es exitoso regresa solo el ID del usuario
            $user = $this->model->login($username, $password);

            if($user != NULL){
                // inicializa el proceso de las sesiones
                $this->getUserSession()->initialize($user);
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