
<?php 

function auth($a){
	$row = squery([
		'get',
		"SELECT * FROM `member` WHERE `username` = '$a[0]' AND `password` ='$a[1]'"
	]);
	if($a[0] === $row[3] && $a[1] === $row[4] && $row[3] != "" && $row[4] != "" && $a[0] != "" && $a[1] != ""){
		set_s(['member',$row]);
		return true;
	}else{
		return false;
	}
}

function randomGen($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

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
