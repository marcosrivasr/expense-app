<?php
    class ErroresModel extends Model{
        //ERROR|SUCCESS
        //Controller
        //method
        //operation
        
        const ERROR_ADMIN_NEWCATEGORY_EXISTS = ["text" => "La categoría ya exista, intenta otra", "hash" => "1f8f0ae8963b16403c3ec9ebb851f156"];

        

        public function __construct(){
            parent::__construct();
        }
        

    }    
?>