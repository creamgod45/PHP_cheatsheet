<?php 

    require_once "exception.php";
    require_once "plugins.php";
    require_once "file.php";

    class pay extends Exception_try {

        private $plugins;
        private $files;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            $this->files = new filesystem();
            return true;
        }

        public function Create_Pay(){}
        public function Updata_Pay(){}
        public function Remove_Pay(){}
        public function Cancel_Pay(){}
        public function SetStaff_Pay(){}
        public function UpdataStaff_Pay(){}
        public function RemoveStaff_Pay(){}
        public function GetPay(){}
        public function GetAllPay(bool $num=false){
            $row = $this->plugins->squery([
                'list',
                "SELECT * FROM `pay`",
                $num
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }
        function Pay(){}
        public function Refund(){}
        public function Certified(){}

        public function test(){
            return true;
        }
    }