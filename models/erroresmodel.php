<?php
    class ErroresModel extends Model{

        private $messages = [
            'error_delete_history' => 'Hubo un problema al realizar la operación'
        ];

        private $hashes = [];

        public function __construct(){
            parent::__construct();

            foreach ($this->messages as $message) {
                array_push($this->hashes, md5(array_keys($message)));
            }
        }

        public function getMessage($hash){
            return array_search($hash, $this->hashes);
            
        }

        public function getHash($id){
            return md5($id);
        }

        

    }    
?>