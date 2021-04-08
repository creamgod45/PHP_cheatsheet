<?php

    use Nette\Utils\FileSystem;
    use Nette\Utils\Json;

    if((@$_SESSION["EXPIRE"]-time()) < 0){
        @$r=$auth->EXPIREMember($plugins->session("member"));
        if(@$r['enable']==="false"){
            $plugins->html_alert_text("會員狀態異常，已經強制登出");
            $plugins->goto_page([1,"/logout"]);
        }elseif(@$r['enable']==="true"){
            $_SESSION["EXPIRE"]=time()+5*60;
        }
    }
    if(@$auth->isMember($plugins->session("member"))){
        if(file_exists("./temp/".$plugins->session("member")['access_token'])){
            $Json = FileSystem::read("./temp/".$plugins->session("member")['access_token']);
            $Json = Json::decode($Json, Json::FORCE_ARRAY);
        
            if($Json['expiretime'] != false and($Json['expiretime']-time()) > 0){
                $expire_message = "登入過期時間剩餘：".$plugins->timestamp( $Json['expiretime']-time(),'i分s秒');
            }elseif($Json['expiretime'] == false){
                $expire_message = "登入過期時間剩餘：無限制";
            }else{
                $plugins->goto_page([0,"/logout"]);
            }
        }else{
            $plugins->goto_page([0,"/logout"]);
        }
    }