<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "regst_lecture";
connectDb();

$me = get_me();

$login = checkLogin($me, ture);
$category_list = getCategoryTree();
//print_r($login);
//print_r($me);

if($_GET['error'] === 1){
    $error = E1KNOT.E2CREATE.'05';
}


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // CFRF
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
                'editer' => $me->id,
        );
        $psheet_parameter = array();
        for($i=1; !empty($psheet_param[$i]); $i++)
            $psheet_parameter['param_'.$i] = $psheet_param[$i];
        $result = registKnot($parameter, $psheet_parameter);
        //        $_SESSION['id_reg']=$id;
        //        exit;
        if($result !== 0 && empty($result)){
            jump("regist_knot", array('err' => E1KNOT.E2CREATE."10"));
        }else{
            jump("knot?id=".$result);
        }
    }

}


// checking

?>



<?php htmlHeader($me, $dir_root, $page_name);?>
<script type="text/javascript">

function crawlLecture(){

}
$(window).load(function() {
	<?php
	$h=<<<tex1
	$("input[name='psheet8']").val("コメント！");
tex1;
	if(empty($psheet_param))echo $h;
	?>
});


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
          *ノット名： <input type="text" name="name"
            value="<?php echo h($name);?>">
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
          タグ(カンマ区切り、最大合計50文字)： <input type="text" name="tags" maxlength="50"
            value="<?php echo h($tags);?>">
          <?php echo h($err['tags']); ?>
        </p>
        <div id="psheet_form">
        Pシート作成
        <?php htmlFormPsheet($psheet_param)?>
        </div>

        </div>
        <input type="hidden" name="token"
          value="<?php echo h($token);?>">
        <p>
          <input type="submit" value="登録！"> <a href="index.php">戻る</a>
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



