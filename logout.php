<?php
//日本語
require_once('require.php');

setupEncodeing();
session_start();

$_SESSION = array();

if(isset($_COOKIE[session_name()])){
	setcookie(session_name(), '', time()-86400, '/denpa/');
}

session_destroy();

header('Location: '.SITE_URL.'login.php');

?>

