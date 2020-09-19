<?php

class Success{
    //ERROR|SUCCESS
    //Controller
    //method
    //operation
    
    const SUCCESS_ADMIN_NEWCATEGORY = "f52228665c4f14c8695b194f670b0ef1";
    
    private $successList = [];

    public function __construct()
    {
        $this->successList = [
            Success::SUCCESS_ADMIN_NEWCATEGORY => "Nueva categoría creada correctamente"
        ];
    }

    function get($hash){
        return $this->successList[$hash];
    }

    function existsKey($key){
        if(array_key_exists($key, $this->successList)){
            return true;
        }else{
            return false;
        }
    }
}
?>