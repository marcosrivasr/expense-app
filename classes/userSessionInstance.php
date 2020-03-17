<?php
    class UserSessionInstance{
        private $session;
        
        function __construct(){
            $this->session = new UserSession();
            $this->validateSession();
        }

        function existsSession(){
            if(isset($this->session)){
                $user = $this->session->getCurrentUser();
                if($user) return true;
                return false;
            }else{
                return false;
            }
        }

        function getUserSessionData(){
            return $this->session->getCurrentUser();
        }

        function validateSession(){
            if($this->existsSession()){
                $user = $this->getUserSessionData();
                
                switch($user['role']){
                    case 'user':
                        header('location: '. constant('URL').'dashboard');
                    break;
    
                    case 'admin':
                        header('location: '. constant('URL').'admin');
                    break;
    
                    default:
    
                }
            }else{

            }
        }
    }
?>