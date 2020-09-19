
<?php

class User extends SessionController{

    function __construct(){
        parent::__construct();
    }

    function render(){
        $user = $this->getUser();
        $this->view->user   = $user;
        $this->view->budget = $user['budget'];
        $this->view->name   = $user['name'];
        $this->view->photo  = $user['photo'];

        $this->view->render('dashboard/user');
    }

    // regresa una función con los datos del usuario
    function getUser(){
        $userid     = $this->getUserId();
        $username   = $this->getUsername();
        $name       = $this->model->getName($userid);
        $photo      = $this->model->getPhoto($userid);
        $budget     = $this->model->getBudget($userid);

        if($name === NULL || empty($username)) $name = $username;
        if($photo === NULL || empty($photo)) $photo = 'default.png';
        if($budget === NULL || empty($budget) || $budget < 0) $budget = 0.0;

        return Array(
            'username'  => $username,
            'name'      => $name,
            'photo'     => $photo,
            'budget'    => $budget
        );
    }

    function updateBudget(){
        if(!isset($_POST['budget'])){
            header('location: ../');
            return;
        }

        $budget = $_POST['budget'];

        if(empty($budget) || $budget === 0 || $budget < 0){
            header('location: '. constant('URL') . 'user');
            return;
        }
    
        $id_user = $this->getUserId();
        $this->model->updateBudget($budget, $id_user);
        header('location: '. constant('URL') . 'user');
    }

    function updateName(){
        if(!isset($_POST['name'])){
            header('location: ../');
            return;
        }

        $name = $_POST['name'];

        if(empty($name)){
            header('location: '. constant('URL') . 'user');
            return;
        }

        $id_user = $this->getUserId();
        $this->model->updateName($name, $id_user);
        header('location: '. constant('URL') . 'user');
    }

    function updatePassword(){
        if(!isset($_POST['current_password']) || !isset($_POST['new_password']) ){
            header('location: ../');
            return;
        }

        $current = $_POST['current_password'];
        $new     = $_POST['new_password'];

        if(empty($current) || empty($new)){
            header('location: '. constant('URL') . 'user');
            return;
        }

        if($current === $new){
            header('location: '. constant('URL') . 'user');
            return;
        }

        $id_user = $this->getUserId();

        //validar que el current es el mismo que el guardado
        if($this->model->comparePasswords($current, $id_user)){
            //si lo es actualizar con el nuevo
            $this->model->updatePassword($new, $id_user);
            header('location: '. constant('URL') . 'user');
        }else{
            header('location: '. constant('URL') . 'user');
            return;
        }
    }

    function updatePhoto(){
        if(!isset($_FILES['photo'])){
            header('location: ../');
            return;
        }
        $photo = $_FILES['photo'];

        $target_dir = "public/img/photos/";
        $extarr = explode('.',$photo["name"]);
        $filename = $extarr[sizeof($extarr)-2];
        $ext = $extarr[sizeof($extarr)-1];
        $hash = md5(Date('Ymdgi') . $filename) . '.' . $ext;
        $target_file = $target_dir . $hash;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        $check = getimagesize($photo["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($photo["tmp_name"], $target_file)) {
                echo "The file ". basename( $photo["name"]). " has been uploaded.";
                //$id_user = $this->getUserSession()->getUserSessionData()['id'];

                $this->model->updatePhoto($hash, $this->user->getId());
                header('location: '. constant('URL') . 'user');
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        
    }
}

?>