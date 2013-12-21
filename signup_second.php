<?php
require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "登録02";

connectDb();


$debug=false;
if($_GET['debug']=="elz"){
    $debug=true;
}
$err=array();
if ($_SERVER['REQUEST_METHOD'] != 'POST' && !$debug) {
    //    if(empty($id))jump('error');
    $id=$_GET['id'];
    $key=$_GET['key'];
    if(empty($id) || empty($key) || !$me=getUser($id, $key, true))$err['system']="acces error";//jump("login");
    /*     echo var_dump($id).B;
     echo var_dump(empty($id)).B;
    echo var_dump($key).B;
    echo var_dump(empty($key)).B;
    echo var_dump(getUser($id,$key,true));*/
    if($me['register_process']=='b')$err['system2']="registed user";//jump('login');
    $token=setToken();
    $_SESSION['id']=$id;
} else {
    $token=checkToken();
    $id=$_SESSION['id'];
    echo $token;
    $local_pref=$_POST['local_pref'];
    $local_city=$_POST['local_city'];
    $local_city_last=$_POST['local_city_last'];
    $graduated_e=$_POST['graduated_e'];
    $graduated_e_private=$_POST['graduated_e_name'];
    $graduated_j=$_POST['graduated_j'];
    $graduated_h=$_POST['graduated_h'];

    $id_twitter=$_POST['id_twitter'];
    //    $id_facebook=$_POST['id_facebook'];
    $id_skype=$_POST['id_skype'];
    $id_pixiv=$_POST['id_pixiv'];
    //    $id_mixi=$_POST['id_mixi'];
    $id_line=$_POST['id_line'];
    $id_tumblr=$_POST['id_tumblr'];

    $private_local=$_POST['private_local-private'];
    $private_graduated_e=$_POST['graduated_e-private'];
    $private_graduated_j=$_POST['graduated_j-private'];
    $private_graduated_h=$_POST['graduated_h-private'];

    $private_id_twitter=$_POST['id_twitter-private'];
    //    $id_facebook=$_POST['id_facebook'];
    $private_id_skype=$_POST['id_skype-private'];
    $private_id_pixiv=$_POST['id_pixiv-private'];
    //    $id_mixi=$_POST['id_mixi'];
    $private_id_line=$_POST['id_line-private'];
    $private_id_tumblr=$_POST['id_tumblr-private'];


    if (scope($lcoal_pref,1,48)) {
        $err['local'] = '不正な入力値です';
    }
    else {
        $selected=$local_pref;
        if(!scope($local_city_last,1,4)){
            $err['local']='不正な入力値です';
        }
        else if ($local_city== '') {
            $err['local'] = '入力されていません';
        }
    }
    if ($graduated_e== '') {
        $err['guraduated_e'] = '入力されていません';
    }
    if ($graduated_j== '') {
        $err['guraduated_j'] = '入力されていません';
    }
    if ($graduated_h== '') {
        $err['guraduated_h'] = '入力されていません';
    }

    if(!isIdChars($id_twitter,true) && !empty($id_twitter)){
        $err['id_twitter']='使えない文字が使われています';
    }
    /*
     if(!isIdChars($id_facebook) && !empty($id_facebook)){
    $err['id_facebook']='使えない文字が使われています';
    }
    */
    if(!isIdChars($id_skype,true) && !empty($id_skype)){
        $err['id_skype']='使えない文字が使われています';
    }
    if(!isIdChars($id_pixiv,true) && !empty($id_pixiv)){
        $err['id_pixiv']='使えない文字が使われています';
    }
    /*
     if(!isIdChars($id_mixi) && !empty($id_mixi)){
    $err['id_mixi']='使えない文字が使われています';
    }
    */
    if(!isIdChars($id_line,true) && !empty($id_line)){
        $err['id_line']='使えない文字が使われています';
    }
    if(!isIdChars($id_tumblr,true) && !empty($id_tumblr)){
        $err['id_tumblr']='使えない文字が使われています';
    }

    if (empty($err)) {
        $name=$name_first." ".$name_last;
        $loca_city.=convertCityToStr($local_city_last);
        $parameter = array(
                'id' => $id,
                'graduated_e' => $graduated_e,
                'graduated_j' => $graduated_j,
                'graduated_h' => $graduated_h,
                'local_pref' => $local_pref,
                'local_city' => $local_city,
                'id_twitter' => $id_twitter,
                'id_skype' => $id_skype,
                'id_pixiv' => $id_pixiv,
                'id_line' => $id_line,
                'id_tumblr' => $id_tumblr,
                'private_local' => $private_local,
                'private_graduated_e' => $private_graduated_e,
                'private_graduated_j' => $private_graduated_j,
                'private_graduated_h' => $private_graduated_h,
                'private_id_twitter' => $private_id_twitter,
                'private_id_skype' => $private_id_skype,
                'private_id_pixiv' => $private_id_pixiv,
                'private_id_line' => $private_id_line,
                'private_id_tumblr' => $private_id_tumblr,
                );
        registUserSecond($parameter);
        jump("login");
    }
}

?>

<?php htmlHeader($me, $dir_root, $page_name);?>
<script>
<!--

//起動時実行
jQuery(document).ready(function($){

});

window.onload=function(){
	switchRadio("local","<?php if($private_local!=2 && $private_local!=3)echo '1';else echo $private_local;?>");
	switchRadio("graduated_e",'<?php if($private_graduated_e!=2 && $private_graduated_e!=3)echo '1';else echo $private_graduated_e;?>');
	switchRadio("graduated_j",'<?php if($private_graduated_j!=2 && $private_graudated_j!=3)echo '1';else echo $private_graduated_j;?>');
	switchRadio("graduated_h",'<?php if($private_graduated_h!=2 && $private_graduated_h!=3)echo '1';else echo $private_graduated_h;?>');
	switchRadio("id_twitter",'<?php if($private_id_twitter!=2 && $private_id_twitter!=3)echo '1';else echo $private_id_twitter;?>');
	switchRadio("id_skype",'<?php if($private_id_skype!=2 && $private_id_skype!=3)echo '1';else echo $private_id_skype;?>');
	switchRadio("id_pixiv",'<?php if($private_id_pixiv!=2 && $private_id_pixiv!=3)echo '1';else echo $private_id_pixiv;?>');
	switchRadio("id_line",'<?php if($private_id_line!=2 && $private_line!=3)echo '1';else echo $private_id_line;?>');
	switchRadio("id_tumblr",'<?php if($private_id_tumblr!=2 && $private_id_tumblr!=3)echo '1';else echo $private_id_tumblr;?>');
}

function switchRadio(id,num){
	$("div#radio-"+id).children("input[type='button']").css("display","block");
	$("div#radio-"+id).children("img").css("display","none");
	$("div#radio-"+id).children("#radio"+num).css("display","none");
	$("div#radio-"+id).children("img#radio-img"+num).css("display","block");
	$("div#radio-"+id).children("input[type='hidden']").attr("value",num);
}






-->
</script>
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
		<?php var_dump($err);?>
			<h1>本登録02</h1>
			<form id="radios" action="" method="POST">
				<div class="input-data">
					<p>
						在住： 都道府県<select name="local_pref">
							<?php htmlPrefecturesPulldwon($selected);?>
						</select> 市町村区<input type="text" name="local_city"
							value="<?php echo h($name_last);?>"> <select
							name="local_city_last">
							<option value="1"
							<?php if(!scope($local_city_last, 2, 4))echo"selected"?>>市</option>
							<option value="2" <?php if($local_city_last=="2")echo"selected"?>>町</option>
							<option value="3" <?php if($local_city_last=="3")echo"selected"?>>村</option>
							<option value="4" <?php if($local_city_last=="4")echo"selected"?>>区</option>
						</select>
						<?php echo h($err['local']); ?>
					</p>
				</div>
				<?php htmlRadio("local");?>
				<div class="input-data">
					<p>
						出身小学校:<input type="text" name="graduated_e"
							value="<?php echo h($graduated_e);?>">小学校
						<?php echo h($err['guraduated_e']); ?>
					</p>
				</div>
				<?php htmlRadio("graduated_e");?>
				<div class="input-data">
					<p>
						出身中学校:<input type="text" name="graduated_j"
							value="<?php echo h($graduated_j);?>">中学校
						<?php echo h($err['guraduated_j']); ?>
					</p>
				</div>
				<?php htmlRadio("graduated_j");?>
				<div class="input-data">
					<p>
						出身高等学校:<input type="text" name="graduated_h"
							value="<?php echo h($graduated_h);?>">高等学校
						<?php echo h($err['guraduated_h']); ?>
					</p>
				</div>
				<?php htmlRadio("graduated_h");?>
				<br> <br>
				<div class="input-data">
					<p>
						TwitterID:<input type="text" name="id_twitter"
							value="<?php echo h($id_twitter);?>">
						<?php echo h($err['id_twitter']); ?>
					</p>
				</div>
				<?php htmlRadio("id_twitter");?>
				<!--
				<p>
					FaceBookId:<input type="text" name="id_facebook"
						value="<?php echo h($id_facebook);?>">
					<?php echo h($err['id_facebook']); ?>
				</p>
-->
				<div class="input-data">
					<p>
						SkypeID:<input type="text" name="id_skype"
							value="<?php echo h($id_skype);?>">
						<?php echo h($err['id_skype']); ?>
					</p>
				</div>
				<?php htmlRadio("id_skype");?>
				<div class="input-data">
					<p>
						PixivID:<input type="text" name="id_pixiv"
							value="<?php echo h($id_pixiv);?>">
						<?php echo h($err['id_pixiv']); ?>
					</p>
				</div>
				<?php htmlRadio("id_pixiv");?>
				<!--
				<p>
					MixiID:<input type="text" name="id_mixi"
						value="<?php echo h($id_mixi);?>">
					<?php echo h($err['id_mixi']); ?>
				</p>
-->
				<div class="input-data">
					<p>
						LineID:<input type="text" name="id_line"
							value="<?php echo h($id_line);?>">
						<?php echo h($err['id_line']); ?>
					</p>
				</div>
				<?php htmlRadio("id_line");?>
				<div class="input-data">
					<p>
						Tumblr:<input type="text" name="id_tumblr"
							value="<?php echo h($id_tumblr);?>">
						<?php echo h($err['id_tumblr']); ?>
					</p>
				</div>
				<?php htmlRadio("id_tumblr");?>

				<input type="hidden" name="token" value="<?php echo $token;?>">
				<div class="input-data">
					<p>
						<input type="submit" value="登録！"> <a href="index.php">戻る</a>
					</p>
				</div>
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
