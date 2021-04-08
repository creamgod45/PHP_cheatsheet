<?php

    // Global Config Environments
    
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
    $auth = new auth();
    $lang = new lang();
    $key = new keys();
    $header = new header();
    $member = new member();
    $table = new table();
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
    }else if($router(1) === "register"){
        include "router/regsiter.php";
    }else if($router(1) === "profile"){        
        include "router/profile.php";
    }else if($router(1) === "logout"){
        include "router/logout.php";
    }else if($router(1) === "table"){
        include "router/table.php";
    }else if($router(1) === "profilelist"){
        include "router/profilelist.php";
    }else if($router(1) === "memberlist"){
        include "router/memberlist.php";
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
