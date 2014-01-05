<?php

require_once('./require.php');
setupEncodeing();

echo "<pre>";

$url = "https://portal.sa.dendai.ac.jp/";
//$url_up = "https://portal.sa.dendai.ac.jp/";
$c = file_get_contents($url);


$c = preg_replace('#(\'|")\.?/#', '\1' . $url, $c);
print_r($url);
//print_r(h($c));

if (!preg_match("/var\surl.*'(.*)';/", $c, $matches)) die('no matche');
$url2 = $matches[1];
echo (PHP_EOL . "matches 1:" . $url2 .PHP_EOL);

$c = file_get_contents($url2);
$url_t = preg_replace('#[^/]*$#', "", $url2);
echo "urlt:" . $url_t . PHP_EOL;
$c = preg_replace('#(\'|")\.?/#', '\1' . $url, $c);

echo(($c));

if (!preg_match_all("#<input.*\s/>#", $c, $matches))die("no find input");
foreach ($matches[0] as $match) {
	echo h($match) . PHP_EOL ;
}

print_r($_COOKIE);
$scs = session_get_cookie_params();
print_r($scs);


//if (!preg_match('/action="(.*jsp)"/', $c, $matches)) die('no matche 2');
//print_r($matches);
//$url3 = $matches[1];
//echo ("matches 2:". $url3. PHP_EOL);
//$url3 = $matches[1];

// ------------------- post method start -------------------//
//POSTデータ
$data = array(
		"form1%3AhtmlUserId" => TDU_ID,
		"form1%3AhtmlPassword" => TDU_PASS,
		"form1%3Alogin.x" => 0,
		"form1%3Alogin.y" => 0,
		"com.sun.faces.VIEW" => "_id8920%3A_id8922",
		"form1" => "form1",
);
$data = http_build_query($data, "", "&");

//header
$header = array(
		"Host: portal.sa.dendai.ac.jp",
//		"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0",
		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
		"Accept-Language: ja,en-us;q=0.7,en;q=0.3",
		"Accept-Encoding: gzip, deflate",
		"Referer: https://portal.sa.dendai.ac.jp/up/faces/up/po/Poa00601A.jsp",
//		"Cookie: FJNADDSPID=2zl3Ns; _ga=GA1.3.218374264.1384840593; JSESSIONID=0000nVFVf17SoEPD7oIWQ5xWH7Y:-1",
		"Connection: keep-alive",
		"Content-Type: application/x-www-form-urlencoded",
		"Content-Length: ".strlen($data),
);

$context = array(
		"http" => array(
				"method"  => "POST",
				"header"  => implode("\r\n", $header),
				"content" => $data
		)
);
// ------------------- post method end -------------------//

$c = file_get_contents($url2, false, stream_context_create($context));
echo (($c));



?>