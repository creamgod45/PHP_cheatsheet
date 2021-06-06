<?php
    session_start();
    date_default_timezone_set("Asia/Taipei");

    // Global Config Environments
    require_once "../../lib/plugins.php";
    require_once "../../lib/conn.php";
    require_once "../../lib/auth.php";
    require_once "../../lib/member.php";

    header('Content-Type: application/json; charset=utf-8');

    @$get_access_token = $_GET['access_token'];

    $plugins = new plugins();
    $conn = new conn(); 
    $auth = new auth();
    $member = new member();

    
    $temp = $member->GetProfile(["access_token"=>$get_access_token], true);
    $plugins->array_splice_key($temp, ['id','theme','banner_url','image_url']);
    if(@$temp['result']==="null"){
        unset($temp);
        $temp = [
            "access_token"=>"無效辨識碼",
            "nickname"=>"查無此人",
            "birthday"=>"無",
            "sex"=>"無",
            "phone"=>"無"
        ];
    }
    foreach ($temp as $key => $value) {
        $temp[$key]=$plugins->default($value,"未設定");
    }
    echo json_encode($temp, JSON_UNESCAPED_UNICODE);

?>