<?php

    use Nette\Utils\FileSystem;
    use Nette\Utils\Json;

    if(@$auth->isMember($plugins->session("member"))){
        if(file_exists("./temp/".$plugins->session("member")['access_token'])){
            $Json = FileSystem::read("./temp/".$plugins->session("member")['access_token']);
            $Json = Json::decode($Json, Json::FORCE_ARRAY);
        
            if($Json['expiretime'] != false and($Json['expiretime']-time()) > 0){
                $expire_message = "登入過期時間剩餘：".$plugins->timestamp('i分s秒', $Json['expiretime']-time());
            }elseif($Json['expiretime'] == false){
                $expire_message = "登入過期時間剩餘：無限制";
            }else{
                $plugins->goto_page([0,"/logout"]);
            }
        }else{
            $plugins->goto_page([0,"/logout"]);
        }
    }