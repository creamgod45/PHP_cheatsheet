<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class lang extends Exception_try {

        use Nette\SmartObject;

        private $plugins;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            return true;
        }

        public function cache_load(){
            $str="";
            $file = fopen("lang.cache","r+");
            if($file != NULL){
                while (!feof($file)) {                
                    $str .= fgets($file);
                }
                fclose($file);
                echo $str;
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