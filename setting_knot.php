<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "ノット設定";
connectDb();

$id = $_GET['id'];
if(empty($id))$id = $_POST['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1KNOT.E2SETTING.'03'));
$knot = getKnot($id);
if(!$knot)
    jump(PAGE_ERROR, array('err' => E1KNOT.E2SETTING.'04'));

$me = get_me();

$login = checkLogin($me, true);
$category_list = getCategoryTree();

$me->set_knots();
if(!$me->isAttendKnot($knot->id)) jump("knot", array('id' => $knot->id, 'err' => E1KNOT.E2SETTING."06"));





if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CFRF
    $name = $knot->name;
    $category = $knot->id_category;
    $tags = $knot->tags;
    $detail = $knot->detail;
    $psheet_param = $knot->getPsheet();
    $token=setToken();
} else {
    $token=checkToken();

    $name = $_POST['name'];
    $category = $_POST['category'];
    $tags = $_POST['tags'];
    $detail = $_POST['detail'];
    $psheet_param = array();
    $c = 1;
    for($i=1; $i <= 8; $i++)
        if(!empty($_POST['psheet'.$i]))
        $psheet_param[$c++] = $_POST['psheet'.$i];

    if($name == ''){
        $err['name'] = '入力されていません';
    }
    if($detail == ''){
        $err['detail'] = '入力されていません';
    }
    if(!empty($tags)) {
        if(mb_strlen($tags) > 50)$err['tags'] = '文字数が不正です';
        $pattern = array("/\s*,\s*/", "/^\s+/", "/\s+$/");
        $replacement = array(",", "", "");
        $tags = preg_replace($pattern, $replacement, $tags);
    }


    if(empty($category)) {
        $err['category'] = '選択されていません';
    }
    if (empty($err)) {
        $parameter = array(
                'name' => $name,
                'detail' => $detail,
                'tags' => $tags,
                'id_category' => $category,
                'editer' => $me->id
        );

        $result = updateKnot($knot, $parameter, $psheet_param);
        if($result === true)
            jump("knot", array("id" => $knot->id), E1CLASS.E2SETTING);
        $err['box'] = E1CLASS.E2SETTING.'08';
    }
}


?>

<?php htmlHeader($me, $dir_root, $page_name, $err['box']);?>

</script>
</head>
<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->


      <form class="regist" action="" method="POST">
        <div class="knot-form-div">



          <p>
            *ノット名：
            <input type="text" name="name" value="<?php echo h($name);?>">
            <?php echo h($err['name']); ?>
          </p>


          <p>
            *カテゴリ:
            <?php htmlCategoryPulldown($category_list, "category", $category);?>
            <?php echo h($err['category']);?>
          </p>
          <p>
            *簡易説明文：
            <textarea name="detail">
            <?=h($detail)?>
            </textarea>
            <?php echo h($err['detail']); ?>
          </p>
          <p>
            タグ(カンマ区切り、最大合計50文字)：
            <input type="text" name="tags" maxlength="50" value="<?php echo h($tags);?>">
            <?php echo h($err['tags']); ?>
          </p>

          <div id="psheet_form">
            Pシート作成
            <?php if(empty($psheet_param))echo "<br>このノットのPシートは空です";?>
            <?php htmlFormPsheet($psheet_param)?>
          </div>
        </div>

        <input type="hidden" name="token" value="<?php echo h($token);?>">
        <input type="hidden" name="id" value="<?php echo h($knot->id);?>">
        <p>
          <input type="submit" value="変更する">
          <a href="index.php">戻る</a>
        </p>
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



