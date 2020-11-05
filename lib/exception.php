<?php 

    require_once "conn.php";

    /**
     * Exception Module
     * @package lib
     */
    class Exception_try {

        /**
         * 連接到資料庫
         *
         * @return Object
         */
        private function conn(){
            $r = new conn();
            return $r->connect();
        }


        public function discover(){
            return true;
        }
    }