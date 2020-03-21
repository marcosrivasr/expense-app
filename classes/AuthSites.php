<?php

class AuthSites{

    private $sites = [
        ['site' => 'dashboard', 'role' => 'user'],
        ['site' => 'admin', 'role' => 'admin']
    ];

    public static function compare($url, $role){
        for($i = 0; $i< sizeof($this->sites); $i++){
            if($url === $this->sites[$i]['site'] && $role === $this->sites[$i]['role']){
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
}

?>