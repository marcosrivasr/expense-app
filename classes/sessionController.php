<?php

require_once 'classes/userSessionInstance.php';
/**
 * Controlador que también maneja las sesiones
 */
class SessionController extends Controller{
    
    private $userSession;
    private $username;
    private $userid;
 
    function __construct(){
        parent::__construct();

        $this->userSession = new UserSessionInstance();
        $this->username    = $this->userSession->getUserSessionData()['username'];
        $this->userid      = $this->userSession->getUserSessionData()['id'];
    }

    public function getUserSession(){
        return $this->userSession;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getUserId(){
        return $this->userid;
    }
}

?>