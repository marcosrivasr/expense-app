
<?php



class Signup extends ControllerSession{

    function __construct(){
        parent::__construct();

        $userSession = new UserSessionInstance();
    }

    function render(){
        $this->view->errorMessage = '';
        $this->view->render('login/signup');
    }

    function newUser(){
        if($this->existPOST(['username', 'password'])){
            
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            //validate data
            if($username == '' || empty($username) || $password == '' || empty($password)){
                // error al validar datos
                $this->errorAtSignup('Campos vacios');
                return;
            }

            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setRole("user");
            //TODO: validar que exista el username
            if($user->save()){
                $this->view->render('login/index');
            }else{
                //error al registrar, que intente de nuevo
                $this->errorAtSignup('Error al registrar el usuario. Elige un nombre de usuario diferente');
                return;
            }
        }else{
            // error, cargar vista con errores
            $this->errorAtSignup('Ingresa nombre de usuario y password');
        }
    }

    function errorAtSignup($err = ''){
        $this->view->errorMessage = $err;
        $this->view->render('login/signup');
    }

    function saludo(){
        $user = new User();
        $res = $user->getAll();
        echo ($res[0]->getId());
    }
}

?>