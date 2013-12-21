<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "講義ノット設定";
connectDb();

$id = $_GET['id'];
if(empty($id))$id = $_POST['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1LECTURE.E2SETTING.'03'));
$lecture = getLecture($id);
if(!$lecture)
    jump(PAGE_ERROR, array('err' => E1LECTURE.E2SETTING.'04'));


$me = get_me();

$login = checkLogin($me, true);
//$category_list = getCategoryTree();
$war = null;

$me->set_knots();
if(!$me->isAttendLecture($lecture->id)) $war['box'] = "あなたはこの講義を受講していません。";
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CFRF
    $token=setToken();
    $name = $lecture->name;
    $detail = $lecture->getDetail();
} else {
    $token=checkToken();
    $name = $_POST['name'];
    $detail = $_POST['detail'];

    if($name == ''){
        $err['name'] = '入力されていません';
    }
    if($detail == ''){
        $err['detail'] = '入力されていません';
    }

    if (empty($err)) {
        $parameter = array(
                'name' => $name,
                'detail' => $detail,
                'editer' => $me->id,
        );

        $result = UpdateLecture($lecture, $parameter);
        //        $_SESSION['id_reg']=$id;
        //        exit;
        if($result === true){
            jump("lecture", array('id' => $lecture->id, 'mes' => E1LECTURE.E2SETTING));
        }
        $err['box'] = E1LECTURE.E2SETTING.'08';
    }
}


?>

<?php htmlHeader($me, $dir_root, $page_name, $err['box']);?>
<script type="text/javascript">

</script>

</head>
<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->


      <form class="regist" action="" method="POST">
        <div class="lecture-form-div">
          <p>
            講義名：
            <input type="text" name="name" value="<?php echo h($name);?>">
            <?php echo h($err['name']); ?>
          </p>
          目的概要：
          <textarea name="detail">
            <?php echo h($detail);?>
            </textarea>
          <?php echo h($err['detail']); ?>

          <input type="hidden" name="token" value="<?php echo h($token);?>">
          <input type="hidden" name="id" value="<?php echo h($knot->id);?>">
          <p>
            <input type="submit" value="変更する">
            <a href="index.php">戻る</a>
          </p>
        </div>
      </form>

    </div>
    <div class="paste-box">
      <textarea id="crawl">

    </textarea>
      <input type="button" id="crawlButton">
    </div>
  </div>
  <?php htmlFooter();?>
</body>
</html>



