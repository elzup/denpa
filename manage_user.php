<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";

$me=$_SESSION['me'];
$login="logout";
if(empty($me)){
    $login="login";
}
/*
 $users = array();
foreach($dbh->query($sql) as $row){
array_push($users, $row);
}
*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset='UTF-8'>
<title>title-DENPA</title>
<?php htmlHeader($me, $dir_root);?>
</head>
<body>
	<div id="header">
		<a href="./"> <img id="logo-header"
			src="./material/logo-header.gif">
		</a> <span id="header-string"><a href="mypage"><?php echo $me['nick_name'];?></a></span>
		<span id="header-string"><a href="<?php echo $login;?>"><?php echo $login;?></a> </span>
	</div>
	<div id="wrapper">
		<div id="wrapper-main">
		<!-- コンテンツスペース -->








		</div>
	</div>
	<div id="footer">
		<ul id="footer-texts">
			<li>このサイトについて</li>
			<li>問い合わせ</li>
			<li>違反の法則</li>
		</ul>
	</div>
</body>
</html>