<?php 

    /**
     * Exception Module
     * @package lib
     */
    class Exception_try {

        use Nette\SmartObject;

        /**
         * 連接到資料庫
         *
         * @return Object
         */
        public function conn(){
            $r = new conn();
            return $r->connect();
        }


        public static function discover(){
            return true;
        }
    }
?>