<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "Knot";
connectDb();

$me = get_me();
$login = checkLogin($me, ture);



$id = $_GET['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1KNOT.E2VIEW.'03'));

$knot = getKnot($id);
if(!$knot)
    jump(PAGE_ERROR, array('err' => E1KNOT.E2VIEW.'04'));
// checking


?>



<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
  <?php htmlHeaderLink($me, $login)?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->
<pre><?php print_r($knot)?>
<?=print_r($knot->getPsheet())?></pre>
<form action="attend_knot" method="POST">
<input type="hidden" name="id" value="<?=$knot->id?>">
<input type="submit" value="参加する">
<a href="setting_knot?id=<?=$knot->id?>">設定</a>
</form>

    </div>
  </div>
  <?php htmlFooter();?>
</body>
</html>
