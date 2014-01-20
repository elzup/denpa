<?php
require_once('../functions.php');

session_start();

$connection = new TwitterOAuth($_SESSION['consumer_key'], $_SESSION['consumer_secret'],
                $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $connection->getAccessToken($_GET['oauth_verifier']);
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

$_SESSION['access_token'] = $access_token;
$ref = $_SESSION['referer'];
unset($_SESSION['referer']);
jump($ref);
?>