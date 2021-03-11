<?php
    if(@$auth->isMember($plugins->session("member"))){
        $header->meta_member("首頁");
        $plugins->html_alert_text("登入中 ".$plugins->session("member")["username"]);
        echo $expire_message;
    }else{
        $header->meta_blank("首頁");
        $plugins->html_alert_text("未登入");
    }
    @$plugins->v($plugins->session('member'));
?>

<body>
    <a href="/register">註冊</a>
    <a href="/login">登入</a>
    <a href="/profile">個人檔案</a>
    <a href="/logout">登出</a>
</body>

</html>