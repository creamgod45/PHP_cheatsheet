<?php
    // Global Config Environments
    session_start();
    date_default_timezone_set("Asia/Taipei");

    header("Content-Type: application/javascript");

    echo '
    var Profile_'.$_SESSION['token'].' = [];
    ';
?>