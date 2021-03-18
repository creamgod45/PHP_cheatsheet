<?php 

    require_once "exception.php";
    require_once "plugins.php";

    class member extends Exception_try {

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

        /**
         * 是否建立個人檔案
         *
         * @param Araay $member
         * @return Boolean
         */
        public function Profile_exist(array $member){
            $access_token = $member["access_token"];
            $row = $this->plugins->squery([
                'get',
                "SELECT * FROM `profile` WHERE `access_token` = '$access_token'"
            ]);
            if(empty($row)) return false;
            return true;
        }

        public function SetProfile(array $member, array $array){
            $a = $member['access_token'];
            $b = $array[0];
            $c = $array[1];
            $d = $array[2];
            $e = $array[3];
            $result = $this->plugins->squery([
                'run',
                "INSERT INTO `profile`(`access_token`, `nickname`, `birthday`, `sex`, `image_url`, `banner_url`, `theme`, `phone`) 
                VALUES ('$a','$b','$c','$d','null','null','default','$e')"
            ]);
            if($result){
                return true;
            }else{
                return false;
            }
        }

        public function UpdateProfile(array $member, array $array, array $prefix_name){
            $a = $member['access_token'];
            $b = $array[0];
            $c = $array[1];
            foreach ($b as $key => $value) {
                if($value===true){
                    $k=$prefix_name[$key];
                    $v=$c[$key];
                    $result = $this->plugins->squery([
                        'run',
                        "UPDATE `profile` SET `$k`='$v' WHERE `access_token` = '$a'"
                    ]);
                    if($result===false){echo $result;   }
                }
            }
            return true;
        }

        /**
         * 取得會員個人資料
         *
         * @param Araay $member
         * @return Array
         */
        public function GetProfile(array $member, bool $true=false){
            $access_token = $member["access_token"];
            $row = $this->plugins->squery([
                'get',
                "SELECT * FROM `profile` WHERE `access_token` = '$access_token'",
                $true
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }

        /**
         * 取得會員個人資料
         *
         * @param Araay $member
         * @return Array
         */
        public function GetAllProfile(){
            $row = $this->plugins->squery([
                'list',
                "SELECT * FROM `profile`"
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }

        public function test(){
            $row = $this->plugins->squery([
                'list',
                "SELECT * FROM `member`"
            ]);
            $this->plugins->v($row);
        }
    }