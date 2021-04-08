<?php 

    require_once "exception.php";
    require_once "plugins.php";
    require_once "keys.php";

    class auth extends Exception_try {

        use Nette\SmartObject;

        private $plugins;

        private $keys;

        /**
         * Init Module Loader
         * @return Boolean
         */
        public function __construct() {
            $this->plugins = new plugins();
            $this->keys = new keys();
            return true;
        }


        public function SetMember(){}
        public function EXPIREMember($member){
            if(empty($member))return false;
            $access_token = $member["access_token"];
            $row = $this->plugins->squery([
                'get',
                "SELECT `enable` FROM `member` WHERE `access_token` = '$access_token'",
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }

        public function GetMember(array $member, bool $num=false){
            $access_token = $member["access_token"];
            $row = $this->plugins->squery([
                'get',
                "SELECT `id`, `username`, `email`, `admin`, `enable`, `created_time` FROM `member` WHERE `access_token` = '$access_token'",
                $num
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }
        public function GetAllMember(bool $num=false){
            $row = $this->plugins->squery([
                'list',
                "SELECT `id`, `username`, `email`, `admin`, `enable`, `created_time` FROM `member`",
                $num
            ]);
            if(empty($row)) return ['result'=>'null'];
            return $row;
        }
        /**
         * 是否為會員
         *
         * @param Array $array
         * @return Boolean
         */
        public function isMember($array){
            $access_token = $array["access_token"];
            $row = $this->plugins->squery([
                'get',
                "SELECT `access_token` FROM `member` WHERE `access_token` = '$access_token' AND `enable` = 'true'"
            ]);
            if($row["access_token"] === $access_token && $row["access_token"] != ""){
                return true;
            }else{
                return false;
            }
        }

        /**
         * 會員登入
         *
         * @param Array $array
         * @return Boolean
         */
        public function Login($array){
            $row = $this->plugins->squery([
                'get',
                "SELECT `password` FROM `member` WHERE `username` = '$array[0]' AND `enable` = 'true'"
            ]);
            if(is_array($row) && !empty($row)){
                $passwd = $this->keys->passwd_decode($array[1],$row[0]);
                if($passwd){
                    $row = $this->plugins->squery([
                        'get',
                        "SELECT `access_token`, `username`, `email`, `admin`, `enable`, `created_time`, `updated_time` FROM `member` WHERE `username` = '$array[0]'"
                    ]);
                    $this->plugins->set_session(['member',$row]);
                    return true;
                }
                return 1;
            }
            return 0;
        }

        /**
         * 會員註冊
         * $array => `access_token`, `username`, `password`, `email`, `admin`, `enable`, `created_time`
         * 
         * @param Array $array
         * @return Integer
         */
        public function Register($array){
            if($array[1] === $array[2]){
                $row = $this->plugins->squery([
                    'get',
                    "SELECT `username` FROM `member` WHERE `username` = '$array[0]'"
                ]);
                if(empty($row)){
                    $passwd = $this->keys->passwd_encode($array[1]);
                    $key = $this->keys->uid();
                    $time = $this->plugins->timestamp();
                    $row = $this->plugins->squery([
                        'run',
                        "INSERT INTO `member`(`access_token`, `username`, `password`, `email`, `admin`, `enable`, `created_time`) 
                        VALUES ('$key','$array[0]','$passwd','$array[3]','$array[4]','$array[5]','$time')"
                    ]);
                    return true;
                }
                return 1;
            }
            return 0;
        }

        public function test(){
            $row = $this->plugins->squery([
                'list',
                "SELECT * FROM `member`"
            ]);
            $this->plugins->v($row);
        }
    }