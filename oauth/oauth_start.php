<?php
require_once('../functions.php');
session_start();

$_SESSION['consumer_key'] = tw_consumer_key;
$_SESSION['consumer_secret'] = tw_consumer_select;
$callbackUri = site_url."oauth/oauth_end.php";

$connection = new TwitterOAuth($_SESSION['consumer_key'], $_SESSION['consumer_secret']);
$request_token = $connection->getRequestToken($callbackUri);
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$authUrl = $connection->getAuthorizeURL($token);

$_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
header('Location: '.$authUrl);
exit;
?>