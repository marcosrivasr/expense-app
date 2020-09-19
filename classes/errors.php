<?php

class Errors{
    //ERROR|SUCCESS
    //Controller
    //method
    //operation
    
    //const ERROR_ADMIN_NEWCATEGORY_EXISTS = "El nombre de la categoría ya existe, intenta otra";
    const ERROR_ADMIN_NEWCATEGORY_EXISTS = "1f8f0ae8963b16403c3ec9ebb851f156";
    private $errorsList = [];

    public function __construct()
    {
        $this->errorsList = [
            Errors::ERROR_ADMIN_NEWCATEGORY_EXISTS => 'El nombre de la categoría ya existe, intenta otra'
        ];
    }

    function get($hash){
        return $this->errorsList[$hash];
    }

    function existsKey($key){
        if(array_key_exists($key, $this->errorsList)){
            return true;
        }else{
            return false;
        }
    }
}
?>