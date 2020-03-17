<?php

require_once 'classes/userSessionInstance.php';

class ControllerSession extends Controller{
    
    private $userSession;
 
    function __construct(){
        parent::__construct();
        $this->userSession = new UserSessionInstance();
    }

    public function getUserSession(){
        return $this->userSession;
    }
}

?>