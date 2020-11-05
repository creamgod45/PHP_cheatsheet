<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class auth extends Exception_try {

        private $plugins;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            return true;
        }

        /**
         * 連接到資料庫
         *
         * @return Object
         */
        private function conn(){
            $r = new conn();
            return $r->connect();
        }

        /**
         * 是否為會員
         *
         * @param Array $array
         * @return Boolean
         */
        public function isMember($array){
            $row = $this->plugins->squery([
                'get',
                "SELECT * FROM `member` WHERE `access_token` = '$array[1]' AND `enable` = 'true'"
            ]);
            if($row[1]===$array[1] && $row[1]!=""){
                return true;
            }else{
                return false;
            }
        }

        /**
         * 會員驗證
         *
         * @param Array $array
         * @return Boolean
         */
        public function Login($array){
            $row = $this->plugins->squery([
                'get',
                "SELECT * FROM `member` WHERE `username` = '$array[0]' AND `password` ='$array[1]'"
            ]);
            if($array[0] === $row[2] && $array[1] === $row[3] && $row[2] != "" && $row[3] != "" && $array[0] != "" && $array[1] != ""){
                $this->plugins->set_session(['member',$row]);
                return true;
            }else{
                return false;
            }
        }

        public function test(){
            $row = $this->plugins->squery([
                'list',
                "SELECT * FROM `member`"
            ]);
            $this->plugins->v($row);
        }
    }