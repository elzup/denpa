<?php

require_once('require.php');
setupEncodeing();
session_start();
$dir_root = "./";
$page_name = "TOP";
connectDb();

$me = get_me();
$login = checkLogin($me);


?>

<?php htmlHeader($me, $dir_root, $page_name);?>
</head>
<body>
  <?php htmlHeaderLink($me, $login);?>
  <div id="wrapper">
  <h1>DENPAトップページ</h1>
    <div id="wrapper-main"></div>
  </div>
  <?php htmlFooter();?>
</body>
</html>
