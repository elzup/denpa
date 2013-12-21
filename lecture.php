<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "Lecture";
connectDb();

$me = get_me();
$login = checkLogin($me, ture);


$id = $_GET['id'];
if(empty($id))
    jump(PAGE_ERROR, array('err' => E1LECTURE.E2VIEW.'03'));
$lecture = getLecture($id);
if(!$lecture)
    jump(PAGE_ERROR, array('err' => E1LECTURE.E2VIEW.'04'));

$childClass = $lecture->getClasses();

?>



<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
  <?php htmlHeaderLink($me, $login)?>
  <div id="wrapper">
    <div class="main">
      <!-- コンテンツスペース -->
      <div id="column-left"></div>
      <div id="column-bigright"></div>


      <a href="./setting_lecture?id=<?=$lecture->id?>">編集する</a>
      <a href="./regist_class?id=<?=$lecture->id?>">クラスを作成する</a>
    </div>
  </div>
  <?php htmlFooter();?>
</body>
</html>
