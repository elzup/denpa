
<?php


require_once('require.php');
$dir_root = "./";
$name = "マイページ";
/* @var $page Page */
$page = new Page ($name, $dir_root);

echo <<<EOF
<!DOCTYPE html>
<html lang="ja">
{$page->head()}
<body>
  {$page->navbar()}
  <div id="wrapper">
    <div class="container">
      <div class="raw">
        <div class="col-sm-12">
        {$page->breadcrumb(array('TOP' => './', 'マイページ' => ACTIVE))}
        </div>
        <div class="col-sm-5">
        {$page->mypage_col_left()}
        </div>
        <div class="col-sm-7">
        {$page->mypage_col_right()}
        </div>
      </div>
    </div>
  </div>
</body>
EOF;

?>

