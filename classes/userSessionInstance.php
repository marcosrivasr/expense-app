<?php

    class UserSessionInstance{
        private $session;
        
        function __construct(){
            $this->session = new UserSession();
            $this->validateSession();
        }

        function existsSession(){
            if(!$this->session->exists()) return false;
            if($this->session->getCurrentUser() == NULL) return false;

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
                //if(AuthSites.getCurrentPage())
                //header('location:/expense-app');
            }
        }

        function authorizeAccess($role){
            $actual_link = trim("$_SERVER[REQUEST_URI]");
            $url = explode('/', $actual_link);
            /*if(!AuthSites.compare($url[2], $role)){
                echo "Si estas autorizado";
            }
            */
            /*switch($role){
                case 'user':
                    //header('location: '. constant('URL').'dashboard');
                    // TODO: validar que no haga ciclo
                break;

                case 'admin':
                    header('location: '. constant('URL').'admin');
                break;

                default:
            }
            */
        }
        
        public function initialize($data){
            
            $this->session->setCurrentUser($data);
            echo var_dump($this->session->getCurrentUser());
            $this->authorizeAccess($data['role']);
        }

        public function test(){
            echo 'Hola';
        }
    }
?>