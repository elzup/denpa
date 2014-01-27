<?php

/* --------------------------------------------------------- *
 *     Setup motional and Connection function
* --------------------------------------------------------- */

function jump($path, Array $parameter){
	$url = $path . "?" . (empty($parameter) ? "" : http_build_query($parameter));
	header('Location: ' . $url);
	exit;
}

function jumpRef() {
	if ($source = $_SESSION['ref']) {
		jump($source);
	}
}

function testFunction(){
	$id_h=rand(0, 100);
	$id_b=rand(100, 999);
	$id=$id_h."fi".$id_b;
	$name="日本語テスト";
	$id_screen="t".$id;
	$nick_name="ニック";
	$sex='m';
	$birthday=101;
	$password='a';
	registUserFirst($id, $name, $id_screen, $nick_name, $sex, $birthday, $password);
}

function getCategoryTree() {
	$json = file_get_contents(SITE_URL."data/category.json");
	$data = json_decode($json);
	return $data;
}

function saveRef() {
	$ref = $_SERVER['HTTP_REFERER'];
	preg_match('#[^/]*$#', $ref, $matches);
	$_SESSION['ref'] = $matches[0];
}


/* --------------------------------------------------------- *
 *     Session functions
* --------------------------------------------------------- */

function setToken(){
	$token = sha1(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
	return $token;
}

function checkToken()
{
	$token=$_POST['token'];
	if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])) {
		echo "不正なPOST";
	}
	return $token;
}









/* --------------------------------------------------------- *
 *     Objective function
* --------------------------------------------------------- */


function sendRegistMail($id, $reg_key){
	$mailadr=$id."@".MAIL_DOMAIN;
	//    $mailadr = "elzzup.htam@i.softbank.jp";
	$to = $mailadr;
	$subject = 'DENPA登録確認メール';
	$message = SITE_URL."signup?id=$id&reg_key=$reg_key";
	//    $mailform = mb_encode_mimeheader('差出人').'<'>MAIL_FROM.'>';
	$mailform = "差出人<".MAIL_FROM.">";
	//    $mailform =MAIL_FROM;
	$headers = 'From: '.$mailform;
	//    $headers = 'From: '.MAIL_FROM;

	$result=mail($to, $subject, $message, $headers);
}




/* --------------------------------------------------------- *
 *      Calcrater functions
* --------------------------------------------------------- */

function checkStateFromTerm($term) {
	$term_y = substr($term, 0, 2);
	$term_t = substr($term, 2, 2);
	$term_n = substr($term, 2, 1);
	$term_l = substr($term, 3, 1);
	if($term_l == 0) {
		$term_now = TERM_Y.substr(TERM_T, 0, 1)."0";
	} else {
		$term_now = TERM;
	}
	return ($term < $term_now) ? "f" : ($term == $term_now) ? "p" : "b";
}


function isTypeId($str){
	if(preg_match("/\d{2}[_a-zA-Z]{2}\d{3}/", $str) && strlen($str)==7)return true;
	return (preg_match("/\d{7}/", $str) && strlen($str)==7) ? 2: false;
}

function is_xss($str) {
	return (preg_match(CH_XSS, $str)) ? true : false;
}
// function isExist($table, $column, $data){
//     $sql = "select * from is_sql($table) where ".is_sql($column)." = '".is_sql($data)."' limit 1";
//     $result=mysql_query($sql);
//     $row=mysql_fetch_row($result);
//     return $row[0];
// }




/* --------------------------------------------------------- *
 *     others
* --------------------------------------------------------- */

function getErrorMessage($num) {
	$me = "";
	switch($num) {
		case 0:
			break;
		default:
			break;
	}
	return $ms;
}


//function is_sql($input) {
//    return mysqli_real_escape_string($input);
//    //    return mysql_real_escape_string("%".$input."%");
//    //    return "%".mysql_real_escape_string($input)."%";
//}

function hashing($str){
	return substr(sha1($str), 0, 20);
}



function super_die($contents) {
	$num = 1;
	echo "<pre>";
	foreach($contents as $key => $value) {
		echo $num." :"."[$key]".PHP_EOL;
		if($value === true) echo "true";
		elseif($value === false) echo "false";
		elseif(empty($value)) echo "empty";
		else {
			print_r($value);
		}
		echo PHP_EOL;
		echo PHP_EOL;
	}
	exit;
}

function h($s){
	return (!empty($s)) ? htmlspecialchars($s, ENT_QUOTES, "UTF-8") : "";
}


?>
