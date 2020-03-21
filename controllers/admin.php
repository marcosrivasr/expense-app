<?php

class Admin extends ControllerSession{


    function __construct(){
        parent::__construct();
    }

    function render(){
        $this->view->render('ayuda/index');
    }
}

?>