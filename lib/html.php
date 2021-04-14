
<?php 

require_once "exception.php";
require_once "plugins.php";
require_once "html.php";

class htmls {

    /**
     * 
     */
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

    public function html_Exception(){}
    public function html_ViewDispatcher(String $type,Mixed $data){
        
    }
    public function html_Builder(Array $array=[]){
        // 初始化
        $code="";
        $tagname=$array["tagname"];
        $config=$array["config"];
        $close=$config["config.close"];
        $body=$array["body"];
        unset($config["config.close"]); // reset config

        // 開始處理
        $code.="<";
        // 標籤名稱
        if(!empty($tagname)){$code.=$tagname;}
        // 標籤設定
        foreach ($config as $key => $value) {
            $code .= " ".$key."=\"$value\"";
        }
        // 一列式標籤
        if($close===false) {
            $code.="/>";
        }else{
            $code.=">";
            // 標籤遞迴
            if(!empty($body)){
                if(is_array($body)){ 
                    // 子陣列處理
                    for ($i=0; $i < count($body); $i++) { 
                        // 循環結構
                        $code.=$this->html_Builder($body[$i]);
                    }
                }elseif(is_string($body)){
                    
                    // 調度員 (dispatcher) 處理
                    if($close==true){$code.=$body;}
                }
            }
            // 標籤結尾
            if($close===true) {$code.="</".$array["tagname"].">";}
        }
        return $code;
    }

    public function test(){
        return true;
    }
}
?>