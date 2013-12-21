<?php
require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "新規登録完了";
if(!empty($_SESSION['me'])){
    jump('');
}


$already=false;
$id=$_GET['id'];
$mailadr=$id."@".MAIL_DOMAIN;
if(isset($_GET['already'])){
    $already=true;

    $textHtml = <<<a
    <p>既に確認メールを送信してあるIDです。</p>
    <p>$mailadr のメールBOXを確認してみてください。</p>
a;
}else{
    $textHtml = <<<b
    <p>$mailadr 宛に@確認メールを送信しました！</p>
    <p>メールのリンクから本登録をしてください</p>
b;
}

?>

<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
	<div id="header">
		<a href="./"> <img id="logo-header" src="./material/logo-header.gif">
		</a> <span id="header-string"><a href="mypage"><?php echo $me['nick_name'];?>
		</a> </span> <span id="header-string"><a href="<?php echo $login;?>"><?php echo $login;?>
		</a> </span>
	</div>
	<div id="wrapper">
		<div id="wrapper-main">
			<h1>仮登録完了</h1>
			<?php
			echo $textHtml;
			?>
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
