<?php
    if(@$auth->isMember($plugins->session("member"))){
        $plugins->html_alert_text("已經登入");
    }else{
        if(@$plugins->post("login")){
            $r = $auth->Login([$plugins->post('username'), $plugins->post('password')]);
            $plugins->v($r);
            //$plugins->v($plugins->session("member"));
        }else{
            echo '

            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>login</title>
                </head>
                <body>
                    <form method="POST">
                        <input type="text" name="username" placeholder="username">
                        <input type="password" name="password" placeholder="password">
                        <input type="submit" name="login">
                    </form>
                </body>
            </html>
            ';
        }
    }
?>