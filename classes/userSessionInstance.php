<?php
    class UserSessionInstance{
        private $session;
        private $accessSites;
        
        function __construct(){
            $this->session = new UserSession();
            $this->validateSession();
        }

        function existsSession(){
            if(!isset($this->session)) return false;
            if($this->session == NULL) return false;

            $user = $this->session->getCurrentUser();
            if($user) return true;

            return false;
        }

        function getUserSessionData(){
            return $this->session->getCurrentUser();
        }

        function validateSession(){
            if($this->existsSession()){
                $user = $this->getUserSessionData();
                $this->authorizeAccess($user['role']);
            }else{
            }
        }

        function authorizeAccess($role){
            switch($role){
                case 'user':
                    header('location: '. constant('URL').'dashboard');
                break;

                case 'admin':
                    header('location: '. constant('URL').'admin');
                break;

                default:
            }
        }

        public function set($data){
            $this->session->setCurrentUser($data);
        }

        public function test(){
            echo 'Hola';
        }
    }
?>