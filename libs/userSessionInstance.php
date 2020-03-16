<?php

    class UserSessionInstance{
        public $session;
        
        function __construct(){
            $this->session = new UserSession();
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

    }
?>