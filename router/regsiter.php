<?php
    $header->meta_member("註冊");
    if(@$auth->isMember($plugins->session("member"))){
        $plugins->html_alert_text("已經登入");
        $plugins->goto_page([2,"/index"]);  
    }else{
        if(@$plugins->post("register")){
            $r = $auth->Register([$plugins->post('username'), $plugins->post('password'), $plugins->post('repassword'), $plugins->post('email'),"false","true"]);
            if($r === true){
                $plugins->result($r, ["註冊成功","註冊失敗",2,"/index"]);
            }else{
                switch($r){
                    case 0:
                        $plugins->html_alert_text("密碼與重複密碼不相同");
                    break;
                    case 1:
                        $plugins->html_alert_text("帳號重複");
                    break;
                }
                $plugins->goto_page([2,"/register"]);
            }
        }else{
            echo '
                <body>
                    <form method="POST">
                        <input type="text" name="username" placeholder="username" requried>
                        <input type="email" name="email" placeholder="email" requried>
                        <input type="password" name="password" placeholder="password" requried>
                        <input type="password" name="repassword" placeholder="repassword" requried>
                        <input type="submit" value="註冊" name="register">
                    </form>
                </body>
            ';
        }
    }
    $header->end();
?>