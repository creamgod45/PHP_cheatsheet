<?php

    require_once "exception.php";
    require 'vendor/autoload.php';
    
    /**
     * Plugins module
     * @package lib
     */
    class plugins extends Exception_try {
        
        use Nette\SmartObject;

        /**
         * 取得有格式的時間字串
         *
         * @param String $format
         * @return String
         */
        public function timestamp($time = null,String $format = 'Y-m-d H:i:s'){
            if(empty($time))$time=time();
            return date($format, $time);
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
        public function squery(Array $array){
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
        public function array_decode(Array $array){
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
        public function array_encode(String $string){
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
        public function html_alert_text(String $string){
            echo "<h1>$string</h1>";
            return true;
        }

        /**
         * HTML 顯示警告訊息(回傳)
         *
         * @param String $string
         * @return String
         */
        public function html_alert_texts(String $string){
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
        public function goto_page(Array $array){
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
        public function result(Bool $boolean, Array $array){
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
        public function findsql(Array $array){
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
        public function router(Int $layer=1){
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
        public function resources(String $path){
            return '//'.$_SERVER['HTTP_HOST'].'/assets/'.$path;
        }

        /**
         * 網站資源索引
         *
         * @param String $path
         * @return String
         */
        public function website_path(String $path){
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
        public function random_not_repeat(Int $min=1, Int $max=100, Int $quantity=5) {
            $numbers = range($min, $max);
            shuffle($numbers);
            return array_slice($numbers, 0, $quantity);
        }

        /**
         * 取得隨機亂碼
         *
         * @param integer $length
         * @return void
         */
        public function Get_eng_randoom($length = 10){
            $str = "";
            $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
            $max = count($characters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = rand(0, $max);
                $str .= $characters[$rand];
            }
            return $str;
        }

        /**
         * IP 位置
         *
         * @return void
         */
        public function GetIP(){
            if(!empty($_SERVER["HTTP_CLIENT_IP"])){
             $cip = $_SERVER["HTTP_CLIENT_IP"];
            }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
             $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }elseif(!empty($_SERVER["REMOTE_ADDR"])){
             $cip = $_SERVER["REMOTE_ADDR"];
            }else{
             $cip = "無法取得IP位址！";
            }
            if($cip === "::1"){
                $cip = '127.0.0.1';
            }
            return $cip;
        }
        
        /**
         * 取得裝置
         *
         * @return void
         */
        public function GetDevice(){
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
                    $Android = true;
            }else if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")){
                    $Android = false;
                    $AndroidTablet = true;
            }else{
                    $Android = false;
                    $AndroidTablet = false;
            }
            $webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
            $BlackBerry = stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
            $RimTablet= stripos($_SERVER['HTTP_USER_AGENT'],"RIM Tablet");
        
            if( $iPod || $iPhone ){
                return 'iPhone';
            }else if($iPad){
                return 'iPad';
            }else if($Android){
                return 'Android';
            }else if($AndroidTablet){
                return 'AndroidTablet';
            }else if($webOS){
                return 'webOS';
            }else if($BlackBerry){
                return 'BlackBerry';
            }else if($RimTablet){
                return 'RimTablet';
            }else{
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $os_platform  = "Unknown OS Platform";
                $os_array     = array(
                                      '/windows nt 10/i'      =>  'Windows 10',
                                      '/windows nt 6.3/i'     =>  'Windows 8.1',
                                      '/windows nt 6.2/i'     =>  'Windows 8',
                                      '/windows nt 6.1/i'     =>  'Windows 7',
                                      '/windows nt 6.0/i'     =>  'Windows Vista',
                                      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                      '/windows nt 5.1/i'     =>  'Windows XP',
                                      '/windows xp/i'         =>  'Windows XP',
                                      '/windows nt 5.0/i'     =>  'Windows 2000',
                                      '/windows me/i'         =>  'Windows ME',
                                      '/win98/i'              =>  'Windows 98',
                                      '/win95/i'              =>  'Windows 95',
                                      '/win16/i'              =>  'Windows 3.11',
                                      '/macintosh|mac os x/i' =>  'Mac OS X',
                                      '/mac_powerpc/i'        =>  'Mac OS 9',
                                      '/linux/i'              =>  'Linux',
                                      '/ubuntu/i'             =>  'Ubuntu',
                                      '/iphone/i'             =>  'iPhone',
                                      '/ipod/i'               =>  'iPod',
                                      '/ipad/i'               =>  'iPad',
                                      '/android/i'            =>  'Android',
                                      '/blackberry/i'         =>  'BlackBerry',
                                      '/webos/i'              =>  'Mobile'
                                );
        
                foreach ($os_array as $regex => $value)
                    if (preg_match($regex, $user_agent))
                        $os_platform = $value;
        
                return $os_platform;
            }
        }

        /**
         * POST 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function post(String $string=""){
            if(@$string==="") return $_POST;
            return $_POST[$string];
        }

        /**
         * REQUEST 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function request(String $string=""){
            if(@$string==="") return $_REQUEST;
            return $_REQUEST[$string];
        }

        /**
         * GET 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function get(String $string=""){
            if(@$string==="") return $_GET;
            return $_GET[$string];
        }

        /**
         * SESSION 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function session(String $string=""){
            if(@$string==="") return $_SESSION;
            return $_SESSION[$string];
        }

        /**
         * FILES 方法
         *
         * @param String $string
         * @return Mixed
         */
        public function files(String $string=""){
            if(@$string==="") return $_FILES;
            return $_FILES[$string];
        }

        /**
         * 設定 SESSION 方法
         *
         * @param Array $array
         * @return Boolean
         */
        public function set_session(Array $array){
            $_SESSION[$array[0]] = $array[1];
            return true;
        }

        /**
         * 設定 SESSION 方法
         *
         * @param Array $array
         * @return Boolean
         */
        public function set_cookie(Array $array=[null,null,0]){
            setcookie( $array[0], $array[1], $array[2]);
            return true;
        }

        /**
         * 檢視導向之物件狀態
         *
         * @param Mixed $mixed
         * @return Boolean
         */
        public function v($mixed){
            dump($mixed);
            return true;
        }

        /**
         * 釘選物件
         *
         * @param Mixed $mixed
         * @return Boolean
         */
        public function pinv($mixed,$string = "Default"){
            bdump($mixed,$string);
            return true;
        }

        /**
         * 字串特徵分解
         *
         * @param String $prefix
         * @param String $string
         * @return Array
         */
        public function exp(String $prefix, String $string){
            return explode($prefix, $string);
        }

        /**
         * md5 訊息摘要演算法
         *
         * @param String $string
         * @return String
         */
        public function m(String $string){
            return md5($string);
        }


        /**
         * Array 指標數值化排序
         *
         * @param Array $array
         * @param Int $offset
         * @return void
         */
        public function array_resort(Array $array, Int $offset=-1,Int $k=0){
            $arr=[];
            foreach ($array as $key => $value) {
                if($offset == (-1)){
                    $arr[$k]=$value;
                    $k++;
                }else{
                    if($k <= count($array)-$offset-1){
                        $arr[$k]=$value;
                        $k++;
                    }
                }
            }
            return $arr;
        }

        /**
         * Array 陣列指標名稱轉換為數字化指標名稱
         *
         * @param Array $array
         * @param Boolean $value
         * @param String $prefix
         * @return void
         */
        public function array_keytovalue(Array $array, bool $value=false,String $prefix=":"){
            $arr=[];
            $k=0;
            if(is_array($array)){
                foreach ($array as $key => $value) {
                    if($value === true){
                        $arr[$k]=$key.$prefix.$value;
                    }else{
                        $arr[$k]=$key;
                    }
                    $k++;
                }
                return $arr;
            }
            return false;
        }

        /**
         * Array 搜尋數值差別
         *
         * @param Array $arr1
         * @param Array $arr2
         * @param boolean $result
         * @param boolean $notfoundmsg
         * @return void
         */
        public function array_diffs(Array $arr1, Array $arr2, bool $result=false, bool $notfoundmsg=false){
            $arr=[];$e=0;
            foreach ($arr1 as $key => $value) {
                if(!empty($arr2[$key])){
                    if(@$arr1[$key]!=$arr2[$key]){$r = true;}else{$r=false;}
                    array_push($arr,$r);
                }else{
                    $e++;
                }
                if($notfoundmsg===true) echo $key." 未找相關指標名稱。<br>";
            }
            if($e>0) return false;
            if($result===true){
                return $arr;
            }
            return true;
        }

        /**
         * Array 由指標群組抽取特定指標
         * 
         * *nametokey
         *  true:(JSON){"0":"1","1":"2","2":"3"}
         *  false:(JSON){"test1":"1","test2":"2","test3":"3"}
         * 
         * *result
         *  true:(PHP)return array()
         *  false:(PHP)return boolean(true)
         *
         * @param Array $array
         * @param Array $keyrows
         * @param boolean $nametokey
         * @return void
         */
        public function array_splice_key(Array &$array, Array $keyrows=null, bool $nametokey=false, bool $result=false, bool $keyint=false){
            $arr=[];$tmp;       
            if($keyrows!=null and is_array($keyrows)){
                if($nametokey===true){
                    $int_arr = $this->array_keytovalue($array);     
                    foreach ($keyrows as $k => $v) {
                        if($result===true){
                            array_push($arr,$array[$int_arr[$v]]);
                        }
                        unset($array[$int_arr[$v]]);
                    }
                }else{
                    foreach ($array as $key => $value) {
                        for ($i=0; $i <= count($keyrows)-1; $i++) {
                            if($key === $keyrows[$i]){
                                if($result===true){
                                    array_push($arr, $array[$key]);
                                }
                                unset($array[$key]);
                            }
                        }
                    }
                }
                if($keyint === true){
                    $array=$this->array_resort($array);
                }
                if($result===true){
                    return $arr;
                }
                return true;
            }else{
                
                return false;
            }
        }

    }
    
?>


