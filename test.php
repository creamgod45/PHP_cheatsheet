
<?php 

function p($a){return $_POST[$a];}
function r($a){return $_REQUEST[$a];}
function g($a){return $_GET[$a];}
function s($a){return $_SESSION[$a];}
function set_s($a){$_SESSION[$a[0]] = $a[1];}
function f($a){return $_FILES[$a];}
function v($a){var_dump($a);}
function e($a){return explode(':',$a);}
function k($v){return md5($v);}
?>
