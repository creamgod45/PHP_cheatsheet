<?php

    // Global Config Environments
    include 'autoreload.php';  // reset class autoload bug :: not rebuild class
    ob_start();
    session_start();
    date_default_timezone_set("Asia/Taipei");
    if($_COOKIE["PHPSESSID"]!=null) setcookie( "PHPSESSID", $_COOKIE["PHPSESSID"], time()+31*24*60*60); // reset PHPSESSID Config
    

    // include module 
    require 'vendor/autoload.php';
    include 'autoload.php';  

    // NAMESPACE
    use Nette\Utils\Arrays;
    use Nette\Utils\Callback;
    use Nette\Utils\DateTime;
    use Nette\Utils\FileSystem;
    use Nette\Utils\Finder;
    use Nette\Utils\Helpers;
    use Nette\Utils\Html;
    use Nette\Utils\Image;
    use Nette\Utils\Json;
    use Nette\Neon\Neon;
    use Nette\Utils\Random;
    use Nette\Utils\Strings;
    use Nette\Utils\Floats;
    use Tracy\Debugger;

    // SETUP VARs
    Debugger::enable();
    $plugins = new plugins();
    $pay = new pay();
    $auth = new auth();
    $lang = new lang();
    $key = new keys();
    $header = new header();
    $member = new member();
    $table = new table();
    $html = new htmls();
    $debug = null;$expire_message = null;

    // logout system
    include 'autologout.php';  
                 
    // DEFAULT FUNCTION
    $router = function($layer){
        $plugins = new plugins();
        return $plugins->router($layer);
    };
    
    // LANGUAGE
    $lang->cache_load();

    // ROUTER
    if($router(1) === "login"){
        include "router/login.php";
    }elseif($router(1) === "register"){
        include "router/regsiter.php";
    }elseif($router(1) === "profile"){        
        include "router/profile.php";
    }elseif($router(1) === "logout"){
        include "router/logout.php";
    }elseif($router(1) === "table"){
        include "router/table.php";
    }elseif($router(1) === "profilelist"){
        include "router/profilelist.php";
    }elseif($router(1) === "memberlist"){
        include "router/memberlist.php";
    }elseif($router(1) === "pay"){
        if(@$router(2) === "list"){
            include "router/paylist.php";
        }elseif(@$router(2) === "manage"){
            include "router/paymanage.php"; 
        }else{
            include "router/pay.php";
        }
    }else{
        include "router/home.php";
    }
    
    // debug
    $plugins->pinv($debug, "DEBUG");
    $plugins->pinv($plugins->session(), "_SESSION");
    $plugins->pinv($plugins->post(), "_POST");
    $plugins->pinv($plugins->get(), "_GET");
    $plugins->pinv($plugins->request(), "_REQUEST");
    $plugins->pinv($plugins->files(), "_FILES");
    $plugins->pinv($expire_message, "expire");
    ob_end_flush();
?>
