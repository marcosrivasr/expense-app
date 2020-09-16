<?php

class View{

    function __construct(){
    }

    function render($nombre, $data = []){
        $this->d = $data;
        error_log("View::render(): data: " . count($this->d) . ' items');
        require 'views/' . $nombre . '.php';
    }
}

?>