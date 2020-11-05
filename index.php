<?php
    session_start();
    require_once "lib/conn.php";
    require "lib/plugins.php";
    require "lib/auth.php";

    $plugins = new plugins();
    $auth = new auth();

?>
