<?php
    // NAMESPACE
    use Nette\Utils\FileSystem;
    use Nette\Utils\Json;

    $header->meta_member("登入");
    if(@$auth->isMember($plugins->session("member"))){
        $plugins->html_alert_text("已經登入");
        $plugins->goto_page([2,"/index"]);
    }else{
        if(
            @$plugins->post("login") and 
            $plugins->session('token') == $plugins->post('token') and 
            empty($_POST['website']) and
            !(isset($_SERVER['HTTP_REFERER']) AND strpos($_SERVER['HTTP_REFERER'], 'http://localhost/') !== 0)
        ){
            
                $r = $auth->Login([$plugins->post('username'), $plugins->post('password')]);
            if($r === true){
                $plugins->result($r, ["登入成功","登入失敗",2,"/index"]);
                if($plugins->post("remeber") == "on"){
                    FileSystem::write("./temp/".$plugins->session("member")['access_token'], Json::encode([
                        "device" => $plugins->GetDevice(),
                        "ip" => $plugins->GetIP(),
                        "time" => time(),
                        "expiretime" => false
                    ], Json::PRETTY | Json::ESCAPE_UNICODE));
                }else{
                    FileSystem::write("./temp/".$plugins->session("member")['access_token'], Json::encode([
                        "device" => $plugins->GetDevice(),
                        "ip" => $plugins->GetIP(),
                        "time" => time(),
                        "expiretime" => time() + 30*60
                    ], Json::PRETTY | Json::ESCAPE_UNICODE));
                }
            }else{
                switch($r){
                    case 0:
                        $plugins->html_alert_text("找不到帳號 或 帳號被停用");
                    break;
                    case 1:
                        $plugins->html_alert_text("密碼錯誤");
                    break;
                }
                $plugins->goto_page([2,"/login"]);
            }
        }else{
            if(@$plugins->session('token')==null) {$plugins->set_session(['token',bin2hex(random_bytes(32))]);}
            
            echo '
                <body>
                    <form method="POST">
                        <input type="text" name="username" placeholder="username">
                        <input type="password" name="password" placeholder="password">
                        <input type="checkbox" name="remeber">記住登入狀態
                        <input type="text" id="website" name="website"/>
                        <input type="hidden" name="token" value="'.$plugins->session('token').'">
                        <input type="submit" value="登入" name="login">
                    </form>
                </body>
            ';
        }
    }
    $header->end();
?>