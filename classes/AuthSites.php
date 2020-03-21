<?php

class AuthSites{


    private $defaultSites = ['user' => 'dashboard', 'admin' => 'admin'];

    public static function compare($url, $role){
        $sites = [
            ['site' => 'dashboard', 'role' => 'user'],
            ['site' => 'admin', 'role' => 'admin'],
            ['site' => '', 'role' => 'all']
        ];
        for($i = 0; $i< sizeof($sites); $i++){
            if($url === $sites[$i]['site'] && $role === $sites[$i]['role']){
                return true;
            }
        }
        return false;
    }

    public static function getCurrentPage(){
        $actual_link = trim("$_SERVER[REQUEST_URI]");
        $url = explode('/', $actual_link);
        return $url[2];
    }

    public static function redirectDefault($role){
        header('location /' . $this->defaultSites[$roles]);
    }
}

?>