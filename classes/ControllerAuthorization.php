<?php

require_once 'classes/userSessionInstance.php';

class ControllerAuthorization extends Controller{
    
    private $userSession;

    function __construct(){
        parent::__construct();
        $userSession = new UserSessionInstance();
    }
}

?>