<?php

if (isset($_POST["url"])) {
	$url = $_POST['url'];
	$file = file_get_contents($url);
	$pattern = '/<img.*src="(.*)".*>/iU';
	//print_r(htmlspecialchars($file));

	$links = array();
	if (preg_match_all($pattern, $file, $matches)) {
		foreach ($matches[1] as $link) {
			$links[] = (isAbsolutePath($link) ? "": $url) . $link;
		}
	}
}

function imgWrap ($path) {
	return '<img class="img_" src="' . $path . '" />';
}
function isAbsolutePath ($path) {
	return substr($path, 0, 4) == "http" || substr($path, 0, 1) == "/";
}
function isRelativePath ($path) {
	return !isAbsolutePath($path);
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>Pic_Extract</title>
<style type="text/css">
.img_ {
	width: 100px;
	height: 100px;
	margin: 5px;
}
</style>
</head>
<body>
  <div class="container" style="margin: 10px">
    <form action="" method="POST">
      URL:
      <input type="text" value="<?=(isset($url) ? $url : "");?>" name="url" />
      <input type="submit" value="" />
    </form>
  </div>

  <div class="container" id="img-div" style="margin: 10px">
    <?php
    if (!empty($links))
    	foreach ($links as $link) {
    	echo imgWrap($link);
    }
    ?>
  </div>

</body>
</html>
