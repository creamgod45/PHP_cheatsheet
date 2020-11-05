<?php
    // Global Config Environments
    session_start();
    date_default_timezone_set("Asia/Taipei");

    // include module 
    require_once "lib/conn.php";
    require "lib/plugins.php";
    require "lib/auth.php";

    // SETUP VAR
    $plugins = new plugins();
    $auth = new auth();

    // FUNCTION
    $router = function($layer){
        $plugins = new plugins();
        return $plugins->router($layer);
    };

    if($router(1) === "login"){
        include "router/login.php";
    }else{
        echo "404";
    }

    //$auth->test();
?>
