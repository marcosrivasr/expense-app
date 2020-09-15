<?php

include_once 'libs/imodel.php';

class Model{

    function __construct(){
        $this->db = new Database();
    }
}

?>