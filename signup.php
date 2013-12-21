<?php
require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "登録";


connectDb();
$id = $_GET['id'];
$reg_key = $_GET['reg_key'];

$err=array();
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    if($id && $reg_key){
        $user=isRegisteredTempUser($id);
//        if($user['ticket']!=$reg_key)jump("error");
        if($user['ticket']!=$reg_key)$err['system']="access error";//echo"ERROR:unable ticket";
        if($userT=getUser($id, '')){
            $err['system02']="access error";//jump("login");
        }
    }
//    else jump("error");
    else $err['system3']="unable request";//echo "ERROR:unable request";
    // CFRF
    $token=setToken();
} else {
    $token=checkToken();

    $name_first = $_POST['name_first'];
    $name_last = $_POST['name_last'];
    $id_screen = $_POST['id_screen'];
    $nick_name=$_POST['nick_name'];
    $sex=$_POST['sex'];
    $birthday_month=$_POST['birthday_month'];
    $birthday_day=$_POST['birthday_day'];
    $password = $_POST['password'];
    $password_check=$_POST['password_check'];


    if ($name_first == '' || $name_last=='') {
        $err['name'] = '入力されていません';
    }
    if ($id_screen== '') {
        $err['id_screen'] = '入力されていません';
    }
    else if(!preg_match("/^[a-zA-Z0-9]+$/", $id_screen)){
        $err['id_screen']='使えない文字が使われています';
    }
    else if(preg_match("/^[0-9]/",$id_screen)){
        $err['id_screen']='数字から始まっています';
    }
    else if(isExist("denpa_user", "id_screen", $id_screen)){
        $err['id_screen']='既に使われているIDです';
    }
    if($nick_name==''){
        $err['nick_name']='入力されていません';
    }
    if($sex!='m' && $sex!='f'){
        $err['sex']='入力値が不正です';
    }
    if(!(scope((int)$birthday_month,1,12) && scope((int)$birthday_day, 1, 31))){
        $err['birthday']='入力値が不正です';
    }
    if($password==''){
        $err['password']='入力されていません';
    }
    if($password_check==''){
        $err['password_check']='入力されていません';
    }
    else if($password_check!=$password){
        $err['password_check']='パスワードと一致しません';
    }

    if (empty($err)) {
        $name=$name_first." ".$name_last;
        $birthday=toDate($birthday_month, $birthday_day);
        $parameter = array(
                'name'      => $name,
                'id_screen' => $id_screen,
                'nick_name' => $nick_name,
                'sex'       => $sex,
                'birthday'  => $birthday,
                'password'  => $password,
                );
        registUserFirst($parameter);
//        $_SESSION['id_reg']=$id;
//        jump("signup_second?id=$id&key=".hashing($password));
        jump("mypage");
    }
}

?>

<?php htmlHeader($me, $dir_root, $page_name);?>
<body>
<?php var_dump($err);?>
	<h1>本登録</h1>
	<form class="regist" action="" method="POST">
		<p>
		氏名：
			<input type="text" name="name_first" value="<?php echo h($name_first);?>">
			<input type="text" name="name_last" value="<?php echo h($name_last);?>">
			<?php echo h($err['name']); ?>
		</p>
		<p>
			スクリーンID:<input type="text" name="id_screen" value="<?php echo h($id_screen);?>">
			<?php echo h($err['id_screen']); ?>
		</p>
		<p>
			ニックネーム:<input type="text" name="nick_name" value="<?php echo h($nick_name);?>">
			<?php echo h($err['nick_name']); ?>
		</p>
		<p>
			性別:<select name="sex">
			<option value="m" <?php if($sex=='m')echo "selected";?>>男</option>
			<option value="f" <?php if($sex=='f')echo "selected";?>>女</option>
			</select>
			<?php echo h($err['sex']); ?>
		</p>
		<p>
			誕生日:
			<input type="number" name="birthday_month" value="<?php echo h($birthday_month);?>">
			<input type="number" name="birthday_day" value="<?php echo h($birthday_day);?>">
			<?php echo h($err['birthday']); ?>
		</p>
		<p>
			パスワード:<input type="password" name="password" value="">
			<?php echo h($err['password']); ?>
		</p>
		<p>
			パスワード確認:<input type="password" name="password_check" value="">
			<?php echo h($err['password_check']); ?>
		</p>
		<input type="hidden" name="token" value="<?php echo h($token);?>">
		<p>
			<input type="submit" value="登録！"> <a href="index.php">戻る</a>
		</p>
	</form>
</body>
</html>
