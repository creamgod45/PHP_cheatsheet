
<?php 

require_once "exception.php";
require_once "plugins.php";
require_once "html.php";

class htmls {

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

    // 調度員 (dispatcher) 方法
    public function html_ViewDispatcher($type,$data){ 
        $html=[];
        switch ($type) {
            case 'pay.staff':
                $this->plugins->pinv($data);
                $Pay = $data[0];
                $key = $data[1];
                $value = $data[2];
                $kk = $data[3];
                @$img = $this->plugins->squery([
                    "get",
                    "SELECT `image_url` FROM `profile` WHERE `access_token` = '$value'"
                ])[0];
                if($img==""){$img = "/user/times-solid.svg";}
                $html="$(\".pf_card".$Pay['pay_token'].$key.$kk."\").ready(async function(){
                    let datas;
                    $.ajax({
                        type: \"GET\",
                        url: \"http://localhost/assets/js/getProfile.json.php?access_token=".$value."\",
                        dataType: \"json\",
                        success: function (response) {
                            datas = response;
                            console.log(datas);
                            $(\".pf_card".$Pay['pay_token'].$key.$kk."\").append('<div class=\"pf_card_element\"><div class=\"pf_card_border\"><div class=\"pf_card_image\"><img src=\"$img\"></div><div class=\"pf_card_title\"><span>'+datas.nickname+'</span></div><div class=\"pf_card_content\"><ul><li>身分號:'+datas.access_token+'</li><li>生日:'+datas.birthday+'</li><li>電話:'+datas.phone+'</li><li>性別:'+datas.sex+'</li></ul></div></div></div>');                            }
                    });
                });";
                break;
            
            default:
                # code...
                break;
        }
        return $this->html_Builder([
            "tagname"=>"script",
            "config"=>[
                "config.close"=>true
            ],
            "body"=>$html
        ]);
    }

    public function html_Builder(Array $array=[]){
        // 初始化
        $code="";
        @$tagname=$array["tagname"];
        @$config=$array["config"];
        @$close=$config["config.close"];
        @$body=$array["body"];
        unset($config["config.close"]); // reset config

        // 開始處理
        if(!empty($tagname))$code.="<";
        // 標籤名稱
        if(!empty($tagname))$code.=$tagname;
        // 標籤設定
        if(!empty($tagname)){
            foreach ($config as $key => $value) {
                $code .= " ".$key."=\"$value\"";
            }
        }
        // 一列式標籤
        if($close===false) {
            // 標籤結尾
            if(!empty($tagname))$code.="/>";
        }else{
            if(!empty($tagname))$code.=">";
            // 標籤遞迴
            if(!empty($body)){
                if(is_array($body)){ 
                    // 子陣列處理
                    if(@$body["type"]!=""){
                        // 調度員 (dispatcher) 處理
                        $code.= $this->html_ViewDispatcher($body["type"], $body["data"]);
                    }else{
                        // 循環結構
                        for ($i=0; $i < count($body); $i++) { 
                            $code.=$this->html_Builder($body[$i]);
                        }
                    }
                }elseif(is_string($body)){
                    // 內容處理
                    if($close==true){$code.=$body;}
                }
            }
            // 標籤結尾
            if($close===true) {$code.="</".$tagname.">";}
        }
        return $code;
    }

    public function test(){
        return true;
    }
}
?>