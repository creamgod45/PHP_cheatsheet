<?php 
    /**
     * connect database
     * @return Array
     */
    class conn {

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