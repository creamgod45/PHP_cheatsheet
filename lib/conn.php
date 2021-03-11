<?php 

    require_once "exception.php";
    
    /**
     * connect database
     * @package lib
     */
    class conn extends Exception_try {

        use Nette\SmartObject;

        /**
         * 連接到資料庫
         *
         * @return Object
         */
        public function connect(){
            $mysqli = mysqli_connect('localhost','root','','php_system');
            //$mysqli = mysqli_connect('localhost','root','','musicbot');
            if($mysqli->connect_error){
                die($mysqli->connect_error);
                return false;
            }
            mysqli_set_charset($mysqli, 'utf8');
            return $mysqli;
        }
    }