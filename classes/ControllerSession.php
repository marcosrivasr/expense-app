<?php

require_once 'classes/userSessionInstance.php';

class ControllerSession extends Controller{
    
    private $userSession;
    private $username;
    private $userid;
 
    function __construct(){
        parent::__construct();
        $this->userSession = new UserSessionInstance();
    }

    public function getUserSession(){
        return $this->userSession;
    }

    public function getUsername(){
        return $this->getUserSession()->getUserSessionData()['username'];
    }

    public function getUserId(){
        return $this->getUserSession()->getUserSessionData()['id'];
    }

    private function loadData(){
        
    }
}

?>