<?php


$url = "http://www.tbs.co.jp/anime/oregairu/special/";

$file = file_get_contents($url);

$pattern = '/<img.*src="(.*)".*>/iU';

//print_r(htmlspecialchars($file));

if (preg_match_all($pattern, $file, $matches)) {
	foreach ($matches[1] as $link) {
		echo imgWrap((isAbsolutePath($link) ? "": $url) . $link);
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