<?php

    require_once "exception.php";
    
    /**
     * Plugins module
     * @package lib
     */
    class plugins extends Exception_try {

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
         * 取得有格式的時間字串
         *
         * @param String $format
         * @return String
         */
        public function timestamp($format = 'Y-m-d H:i:s'){
            return date($format);
        }

        /**
        * 執行資料庫指令
        * 
        * squery( [ '{service_name}', "{SQL}" ] );
        * {service_name} => 'get', 'list', 'run'
        * {SQL}          => 資料庫指令
        *
        * @param  Array $array
        * @return Array
        */
        public function squery($array){
            $conn = $this->conn();
            switch($array[0]){
                case 'get':
                    $result = $conn->query($array[1]);
                    if(@$array[2]){
                        $row = mysqli_fetch_assoc($result);
                    }else{
                        $row = mysqli_fetch_array($result);
                    }
                    $conn->close();
                    return $row;
                break;
                case 'list':
                    $x = 1;
                    $obj = [];
                    $result = $conn->query($array[1]);
                    if(@$array[2]){
                        while($row = mysqli_fetch_assoc($result)){
                            $obj[$x]=$row;
                            $x++;
                        }
                    }else{
                        while($row = mysqli_fetch_array($result)){
                            $obj[$x]=$row;
                            $x++;
                        }
                    }
                    $conn->close();
                    return $obj;
                break;
                case 'run':
                    $conn->query($array[1]);
                    if($conn->error){
                        echo $conn->error;
                        $conn->close();
                        return false;
                    }else{
                        $conn->close();
                        return true;
                    }
                break;
            }
        }

        /**
         * 陣列轉字串
         * 
         * input:
         * {
         * 		[0] => [
         * 			[0]=>0,
         * 			[1]=>1
         * 		],
         * 		[1] => [
         * 			[0]=>2,
         * 			[1]=>3,
         * 			[2]=>4
         * 		]
         * }
         * 
         * output:
         * /0:1/2:3:4
         *
         * @param Array $array
         * @return String
         */
        public function array_decode($array){
            $string = "";
            for($i=0;$i<=count($array)-1;$i++){
                if($i===0){
                    for($y=0;$y<=count($array[$i])-1;$y++){
                        if($y==count($array[$i])-1){
                            $string .= $array[$i][$y];
                        }else{
                            $string .= $array[$i][$y].':';
                        }
                    }
                }else{ 
                    $string .= '/';
                    for($y=0;$y<=count($array[$i])-1;$y++){
                        if($y==count($array[$i])-1){
                            $string .= $array[$i][$y];
                        }else{
                            $string .= $array[$i][$y].':';
                        }
                    }
                }
            }
            return $string;
        }

        /**
         * 字串轉陣列
         * 
         * input:
         * /0:1/2:3:4
         * 
         * output:
         * {
         * 		[0] => [
         * 			[0]=>0,
         * 			[1]=>1
         * 		],
         * 		[1] => [
         * 			[0]=>2,
         * 			[1]=>3,
         * 			[2]=>4
         * 		]
         * }
         *
         * @param  String $string
         * @return Array
         */
        public function array_encode($string){
            $array = [];
            $b = explode('/', $string);
            for($i=0;$i<=count($b)-1;$i++){
                $d = [];
                $c = explode(':',$b[$i]);
                for($y=0;$y<=count($c)-1;$y++){
                    $d[$y] = $c[$y];
                }
                $array[$i] = $d;
            }
            
            return $array;
        }
        
        /**
         * HTML 顯示警告訊息
         *
         * @param String $string
         * @return Boolean
         */
        public function html_alert_text($string){
            echo "<h1>$string</h1>";
            return true;
        }

        /**
         * HTML 顯示警告訊息(回傳)
         *
         * @param String $string
         * @return String
         */
        public function html_alert_texts($string){
            return "<h1>$string</h1>";
        }

        /**
         * 前往指定網頁
         * [0] => 秒數
         * [1] => 地址
         *
         * @param Array $array
         * @return Boolean
         */
        public function goto_page($array){
            header('refresh:'.$array[0].';url="'.$array[1].'"');
            return true;
        }

        /**
         * 回應式 
         *  
         * $array 
         * [0] => 如果 $boolean 為 true 顯示文字
         * [1] => 如果 $boolean 為 false 顯示文字
         * [2] => 秒數
         * [3] => 地址
         * 
         * @param Boolean $boolean
         * @param Array $array
         * @return Boolean
         */
        public function result($boolean, $array){
            if($boolean){
                echo "<h1>$array[0]</h1>";
                header('refresh:'.$array[2].';url="'.$array[3].'"');
            }else{
                echo "<h1>$array[1]</h1>";
                header('refresh:'.$array[2].';url="'.$array[3].'"');
            }
            return true;
        }
        
        /**
         * 快速找尋資料欄位
         * 
         * $array
         * [0] => 資料表名稱
         * [1] => 欄位
         * [2] => 數值
         *
         * @param Array $array
         * @return Array
         */
        public function findsql($array){
            $sql = "SELECT * FROM `$array[0]` WHERE `$array[1]` = '$array[2]'";
            return squery([
                'get',
                $sql
            ]);
        }
        
        /**
         * 網站路由
         *
         * @param Integer $layer
         * @return String
         */
        public function router($layer=1){
            $url = $_SERVER['REQUEST_URI'];
            $REQUEST = explode("/", $url);
            $REQUEST = $REQUEST[$layer];
            return $REQUEST;
        }

        /**
         * 網站財產資源索引
         *
         * @param String $path
         * @return String
         */
        public function resources($path){
            return '//'.$_SERVER['HTTP_HOST'].'/assets/'.$path;
        }

        /**
         * 網站資源索引
         *
         * @param String $path
         * @return String
         */
        public function website_path($path){
            return '//'.$_SERVER['HTTP_HOST'].'/'.$path;
        }

        /**
         * 取 $quantity 個 亂數且不重複
         *
         * @param Integer $min
         * @param Integer $max
         * @param Integer $quantity
         * @return Array
         */
        public function random_not_repeat($min=1, $max=100, $quantity=5) {
            $numbers = range($min, $max);
            shuffle($numbers);
            return array_slice($numbers, 0, $quantity);
        }

        /**
         * POST 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function post($string){
            return $_POST[$string];
        }

        /**
         * REQUEST 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function request($string){
            return $_REQUEST[$string];
        }

        /**
         * GET 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function get($string){
            return $_GET[$string];
        }

        /**
         * SESSION 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function session($string){
            return $_SESSION[$string];
        }

        /**
         * FILES 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function files($string){
            return $_FILES[$string];
        }

        /**
         * 設定 SESSION 方法
         *
         * @param Array $array
         * @return Boolean
         */
        public function set_session($array){
            $_SESSION[$array[0]] = $array[1];
            return true;
        }

        /**
         * 檢視導向之物件狀態
         *
         * @param Mixed $mixed
         * @return Boolean
         */
        public function v($mixed){
            var_dump($mixed);
            return true;
        }

        /**
         * 字串特徵分解
         *
         * @param String $prefix
         * @param String $string
         * @return Array
         */
        public function exp($prefix,$string){
            return explode($prefix, $string);
        }

        /**
         * md5 訊息摘要演算法
         *
         * @param String $string
         * @return String
         */
        public function m($string){
            return md5($string);
        }

    }
    
?>


