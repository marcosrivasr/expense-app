<?php
    require_once 'classes/AuthSites.php';
    /**
     * Maneja los accesos de las sesiones
     */
    class UserSessionInstance{
        private $session;

        private $sites;

        private $defaultSites;
        
        function __construct(){
            //se crea nueva sesión
            $this->session = new Session();
            //se carga el archivo json con la configuración de acceso
            $json = $this->getJSONFileConfig();
            // se asignan los sitios
            $this->sites = $json['sites'];
            // se asignan los sitios por default, los que cualquier rol tiene acceso
            $this->defaultSites = $json['default-sites'];
            // inicia el flujo de validación para determinar
            // el tipo de rol y permismos
            $this->validateSession();
        }

        private function getJSONFileConfig(){
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
                error_log("userSessionInstance::validateSession(): " . $this->getUserSessionData());
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
                //No existe ninguna sesión
                //se valida si el acceso es público o no
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