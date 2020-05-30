<?php
    require_once 'classes/AuthSites.php';

    class UserSessionInstance{
        private $session;

        private $sites;

        private $defaultSites;
        
        function __construct(){
            $this->session = new UserSession();
            $json = $this->loadAccesses();
            $this->sites = $json['sites'];
            $this->defaultSites = $json['default-sites'];
            $this->validateSession();
        }

        private function loadAccesses(){
            $string = file_get_contents("config/access.json");
            $json = json_decode($string, true);

            return $json;
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
            //Si existe sesión
            if($this->existsSession()){
                $role = $this->getUserSessionData()['role'];
                if($this->isPublic()){
                    $this->redirectDefaultByRol($role);
                }else{
                    if($this->isAuthorized($role)){
                        //no pasa nada, deja pasar
                    }else{
                        //
                        $this->redirectDefaultByRol($role);
                    }
                }
            }else{
                if($this->isPublic()){
                    //la pagina es publica
                    //no pasa nada
                }else{
                    //la página no es pública
                    //redirect al login
                    header('location: '. constant('URL') . '');
                }
            }
        }
        
        public function initialize($data){
            $this->session->setCurrentUser($data);
            $this->authorizeAccess($data['role']);
        }

        public function test(){
            echo 'Hola';
        }

        private function isPublic(){
            $currentURL = $this->getCurrentPage();
            for($i = 0; $i < sizeof($this->sites); $i++){
                if($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['access'] === 'public'){
                    return true;
                }
            }
            return false;
        }
        private function redirectDefaultByRol($role){
            $url = '';
            for($i = 0; $i < sizeof($this->sites); $i++){
                if($this->sites[$i]['role'] === $role){
                    $url = '/expense-app/'.$this->sites[$i]['site'];
                break;
                }
            }
            header('location: '.$url);
            
        }

        private function isAuthorized($role){
            $currentURL = $this->getCurrentPage();
            for($i = 0; $i < sizeof($this->sites); $i++){
                if($currentURL === $this->sites[$i]['site'] && $this->sites[$i]['role'] === $role){
                    return true;
                }
            }
            return false;
        }

        private function getCurrentPage(){
            $actual_link = trim("$_SERVER[REQUEST_URI]");
            $url = explode('/', $actual_link);
            return $url[2];
        }
    }
?>