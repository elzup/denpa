<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "Class";
connectDb();

$me = get_me();
$login = checkLogin($me, ture);

$id = $_GET['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1CLASS.E2VIEW.'03'));

$class = getClass($id);
if(!$class)
    jump(PAGE_ERROR, array('err' => E1CLASS.E2VIEW.'04'));

// checking


?>



<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
  <?php htmlHeaderLink($me, $login)?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->
      <pre>
        <?php print_r($class)?>
        getMinEmptyId = :
        <?php echo DB::getMinEmptyId("denpa_knot_class", "id_class");?>
      </pre>
      <form action="attend_class" method="POST">
        <input type="hidden" name="id" value="<?=$class->id?>"> <input
          type="submit" value="受講">
      </form>
      <a href="./setting_class?id=<?=$class->id?>">編集する</a>

    </div>
  </div>

  <?php htmlFooter();?>
</body>
</html>
