<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "ログイン";


//check the state logining
if (!empty($_SESSION['me'])){
    //if logined move top page
    header('Location: '.SITE_URL);
    exit;
}
if (!empty($_POST['error'])){
    $error_message="Loginされていません";
}
//$jumpSource = isset($_GET['ret']);
saveRef();

$name     = $_COOKIE['name'];
$password = $_COOKIE['pass'];



if ($_SERVER['REQUEST_METHOD'] != 'POST'){
    $token=setToken();
}
else{
    $token=checkToken();

    $name = $_POST['name'];
    $password = $_POST['password'];

    connectDb();
    $err = array();
    //ネームが空
    if ($name=='') $err['name']='入力されていません';

    //パスワードが空
    if ($password=='') $err['password']='入力されていません';

    //パスワードかネーム正しくない
    if (empty($err)) {
        $me = getUser($name, $password);
        if (!$me) {
            $err['password']='IDかパスワードが間違っています';
        } else {
            set_me($me);
            setcookie('name', $name,     60 * 60 * 24 * 30);
            setcookie('pass', $password, 60 * 60 * 24 * 30);
            jumpRef();

            jump($target);
            //            exit;
        }
    }
}




?>

<title>ログイン-DENPA</title>
<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
  <?php htmlHeaderLink($me);?>
  <div id="wrapper">
    <div id="wrapper-main" style="float: left;">
      <div id="login-box">
        <div class="top">ログイン</div>
        <div class="middle">
          <form action="" method="POST">
            <div class="id-div">
              <input type="text" name="name" placeholder=" user ID" value="<?php echo h($name); ?>">
              <?php echo h($err['name']); ?>
            </div>
            <div class="pass-div">
              <input type="password" name="password" placeholder=" password" value="">
              <?php echo h($err['password']); ?>
            </div>
            <input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
            <div class="submit-div">
              <input type="submit" value="ログイン">
            </div>
            <div class="regist-div">
              <a href="signup_pre">新規登録</a>
            </div>
          </form>
        </div>
        <div class="bottom"></div>
      </div>
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
