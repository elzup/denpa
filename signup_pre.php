<?php
require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "新規登録";

if(!empty($_SESSION['me'])){
    jump('');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CFRF
    $token=setToken();
} else {
    $token=checkToken();
    $name = $_POST['name'];
    //    $password = $_POST['password'];

    connectDb();
    $err = array();

    if ($name == '') {
        $err['name'] = '入力されていません';
    }
    else if(!isTypeId($name)){
        $err['name'] = '学籍番号ではありません';
    }
     else if (getUser($name) !== false) {
         $err['name'] = '既に登録されています';
     }
//    $err['name'] = '既に登録されています';
    //     if (empty($password)) {
    //         $err['password'] = '入力されていません';
    //     }

    if (empty($err)) {
        $name=strtolower($name);
        $user=isRegisteredTempUser($name);
        echo $user;
        if($user && $user!=1){
            jump("signup_pre_end?already=1&id=$name");
            exit();
        }
        else{                //new Register work
            $reg_key = sha1(uniqid(rand(), 1));
            registTempUser($name, $reg_key);

            sendRegistMail($name, $reg_key);
            jump("signup_pre_end?id=$name");
        }
    }
}

?>

<?php htmlHeader($me, $dir_root, $page_name);?>
<body>
	<div id="header">
		<a href="./"> <img id="logo-header"
			src="./material/logo-header.gif">
		</a> <span id="header-string"><a href="mypage"><?php echo $me['nick_name'];?></a></span>
		<span id="header-string"><a href="<?php echo $login;?>"><?php echo $login;?></a> </span>
	</div>
	<div id="wrapper">
		<div id="wrapper-main">

			<h1>新規登録</h1>
			<form action="" method="POST">
				<p>
					学籍番号:<input type="text" name="name" value="<?php echo h($name);?>">
					<?php echo h($err['name']); ?>
				</p>
				<input type="hidden" name="token" value="<?php echo $token;?>">
				<p>
					<input type="submit" value="登録！"> <a href="index.php">戻る</a>
				</p>
			</form>
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
