<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class filesystem extends Exception_try {

        private $plugins;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            return true;
        }

        public function FileUpload(){}
        public function FileDownload(){}
        public function FilePackage(){}
        public function FileRequest(){}
        public function FileStream(){}

        public function test(){
            return true;
        }
    }