
<?php

class Dashboard extends ControllerSession{

    function __construct(){
        parent::__construct();
    }

    function render(){
        $this->view->render('dashboard/index');
    }

    function saludo(){
        $actual_link = trim("$_SERVER[REQUEST_URI]");
        $url = explode('/', $actual_link);
        echo $url[2];

    }
}

?>