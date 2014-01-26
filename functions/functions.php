<?php

/* --------------------------------------------------------- *
 *     Setup motional and Connection function
* --------------------------------------------------------- */

function jump($path, Array $parameter){
	$url = SITE_RUL . $path. (empty($parameter) ? "" : http_build_query($parameter));
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



function set_me($me){
	session_regenerate_id(true);
	$_SESSION['me'] = $me->get_serialize();
}








/* --------------------------------------------------------- *
 *     Objective function
* --------------------------------------------------------- */

function registUserFirst($parameter){
	$parameter['id'] = convertIdTonum($id);
	$parameter['password'] = hashing($parameter['password']);
	$reg_id = DB::getMinEmptyId("denpa_user", "number");
	$parameter['number'] = $reg_id;
	$parameter['mail'] = 'NULL';
	$parameter['point'] = '10000';
	$parameter['time_register'] = 'NOW()';
	$parameter['register_process'] = 'NOW()';

	$result = DB::insert('denpa_user', $parameter);
}

/*
 * 世界のゴミ
function registUserSecond($parameter){
$parameter['id'] =convertIdToNum($id);
foreach ($parameter as &$p) $p = is_sql($p);
$sql = "UPDATE `denpa_user`
SET
`mail` = NULL, `time_register` = NOW(),
`graduated_e` = {$parameter['graduated_e']}, `graduated_j` = {$parameter['graduated_j']}, `graduated_h` = {$parameter['graduated_h']},
`lcoal_pref` = {$parameter['local_pref']}, `local_city` = {$parameter['local_city']},
`id_twitter` = {$parameter['id_twitter']}, `id_skype` = {$paraemter['id_skype']},
`id_pixiv` = {$parameter['id_pixiv']}, `id_line` = {$parameter['id_line']},
`id_tumblr` = {$parameter['id_tumblr']},
`private_graduated_e` = {$parameter['private_graduated_e']}, `private_graduated_j` = {$parameter['private_graduated_j']}, `private_graduated_h` = {$parameter['private_graduated_h']},
`private_local` = {$parameter['private_local']},
`private_id_twitter` = {$parameter['private_id_twitter']}, `private_id_skype` = {$parameter['private_id_skype']},
`private_id_pixiv` = {$parameter['private_id_pixiv']}, `private_id_line` = {$parameter['private_id_line']},
`private_id_tumblr` = {$parameter['private_id_tumblr']},
`register_process` = 'b'
WHERE `id` = ".$parameter['id']." LIMIT 1";
$result = mysql_query($sql)or die("ERROR::".mysql_error()."\n$sql");
return $result;
}
*/


function registTempUser($name, $reg_key){
	$id =convertIdToNum($name);
	$parameter = array('time_register' => 'NOW()', 'ticket' => $reg_key);
	if(isRegisteredTempUser($name) == 1){
		$condition = array('id' => $id);
		$result = DB::update($table_name = 'denpa_user_temp', $parameter, $condition, 1);
	}
	else{
		$result = DB::insert($table_name = 'denpa_user_temp', $parameter);
	}
	return !!$result;
}

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

function getUser($name , $password='', $shaed=false, $compact = false) {
	if($password == '') {
		$idTemp = convertIdToNum($name);
		$recos = DB::getTable('denpa_user', null, array('id' => $idTemp), 1);
		$user_data = $recos[0];
	} else {
		if(!$shaed)$password = hashing($password);
		$recos = array();
		if(isTypeId($name)){
			$idTemp=convertIdToNum($name);
			$recos = DB::getTable('denpa_user', null, array('id' => $idTemp, 'password' => $password), 1);
		}
		else{
			$recos = DB::getTable('denpa_user', null, array('id_screen' => $name, 'password' => $password), 1);
			//            $sql = "select * from denpa_user where id_screen= $name and password= $password limit 1";
		}
		$user_data = $recos[0];
	}
	if($user_data['register_process'] == 'a' && !$shaed){
		jump("signup_second?id=".convertIdToStr($user_data['id'])."&key=".$user['password']);
	}
	return empty($user_data) ? false : new User($user_data, $compact);
}

function isRegisteredTempUser($id){
	$idTemp = convertIdToNum($id);
	$condition = array('id' => $idTemp);
	$recos = DB::getTable($table_name = 'denpa_user_temp', null, $condition, 1);
	$user = $recos[0];
	if(!$user)return false;
	$registTimer=strtotime($user['time_register']);
	if(time()<strtotime("+2 day", $registTimer)) {
		return $user;
	}else{
		$result = DB::delete($table_name = 'denpa_user_temp', $condition);
		return 1;
	}
}

function registKnot($parameter, $psheet_param) {
	foreach ($parameter as &$p) $p = is_sql($p);
	$reg_id = DB::getMinEmptyId("denpa_knot", "id_knot");
	$parameter['id_knot'] = $reg_id;
	$result = DB::insert($table_name = 'denpa_knot', $parameter);
	if($result) {
		$reg_id = $table_data['Auto_increment'] - 1;
		if(!empty($psheet_param['param_1'])) {
			$result3 = registPsheet($registed_id, $psheet_param);
		}
		return $reg_id;
	}
	return false;
}

function updateKnot(Knot $knot, $parameter, $psheet_param) {
	$err = null;
	$parameter_pre = $knot->getArrayParams();
	$editer = $parameter['editer'];
	unset($parameter['editer']);
	$sql = "UPDATE `denpa_knot` SET";
	foreach($parameter as $key => $value) {
		if(empty($value) || $parameter_pre[$key] == $value) {
			unset($parameter[$key]);
		}
	}
	if(!empty($parameter)) {
		$condition = array ('id_knot' => $knot->id);
		DB::update($table_name = 'denpa_knot', $parameter, $condition, 1);
	}
	if(!empty($psheet_param))updatePsheet($knot, $psheet_param);
	return (empty($err)) ? true: $err;
}


function registPsheet($id_knot, $parameter) {
	return $result = DB::insert($table_name = 'denpa_psheet', $parameter);
}

function updatePsheet(Knot $knot, $parameter) {
	$psheet_param_pre = $knot->getPsheet();
	if($psheet_param_pre === false) {
		return registPsheet($knot->id, $psheet_param);
	}
	for ( $i = 1; !empty($parameter['param_'.$i]); $i++) {
		$value = $parameter["param_".$i];
		if(empty($value) || $psheet_param_pre[$i] == $value)
			unset($parameter[$key]);
	}
	if(!empty($parameter)) {
		$condition = array('id_knot' => $knot->id);
		$result = DB::update($table_name = 'denpa_psheet', $parameter, $condition);
		return $result;
	}
	return false;
}

function registLecture($parameter) {
	$reg_id = DB::getMinEmptyId("denpa_knot_lecture", "id_lecture");
	$parameter['id_lecture'] = $reg_id;
	//    print_r($parameter);
	//    exit;
	$result = DB::insert($table_name = 'denpa_knot_lecture', $parameter);
	return empty($result) ? false : $reg_id;
}

function updateLecture (Lecture $lecture, $parameter) {
	$err = null;
	$parameter_pre = $lecture->getArrayParams();
	foreach($parameter_pre as $key => $value_pre) {
		$value = $parameter[$key];
		if(empty($value) || $value_pre == $value)
			unset($parameter[$key]);
	}
	if(!empty($parameter)) {
		$condition = array('id_lecture' => $lecture->id);
		$result = DB::update($table_name = 'denpa_knot_lecture', $parameter, $condition);
	} else {
		$err = "変更点がありません";
	}
	return (empty($err)) ? true: $err;
}

function registClass($parameter) {
	foreach ($parameter as &$p) $p = is_sql($p);
	$reg_id = DB::getMinEmptyId("denpa_knot_class", "id_class");
	$parameter['id_class'] = $reg_id;


	//     $sql = sprintf("INSERT INTO `denpa_knot_class` (`id_class`, `id_root`, `schedules`, `room`, `teacher`, `limit`, `textbook`, `measurement`, `prepare`)
	//             VALUES ('$reg_id', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
	//             $parameter['root'], $parameter['schedule'], $parameter['room'], $parameter['teacher'],
	//             $parameter['limit'], $parameter['textbook'], $parameter['measurement'], $parameter['prepare']);
	//     $result = mysql_query($sql)or die("ERROR::".mysql_error()."\n$sql");
	$result = DB::insert($table_name = 'denpa_knot_class', $parameter);
	if($result) {
		return $reg_id;
	}
	return empty($result) ? false : $reg_id;
}

function updateClass (LClass $class, $parameter) {
	$err = null;
	foreach ($parameter as &$p) $p = is_sql($p);
	$parameter_pre = $class->getArrayParams();
	foreach($parameter_pre as $key => $value_pre) {
		$value = $parameter[$key];
		if(empty($value) || $value_pre == $value)
			unset($parameter[$key]);
	}
	if(!empty($parameter)) {
		$condition = array ('id_class' => $class->id);
		$result = DB::update($table_name = 'denpa_knot_class', $parameter, $condition);
	} else {
		$err = '変更点がありません';
	}
	return (empty($err)) ? true: $err;
}

function attendClass(User $user, LClass $class) {
	$parameter = array(
			'id_student' => $user->id,
			'id_class'   => $class->id,
			//            'time_register' => 'NOW()',
	);
	return $result = DB::insert($table_name = 'denpa_attend_class', $parameter);
}

function checkAttend(User $user, LClass $class, &$matched = null){
	//    echo count($class->schedules);
	for($i = 0; $i < count($class->schedules); $i++) {
		$cd = $class->schedules_d[$i];
		$ct = $class->schedules_t[$i];
		if($user->getScheduleClass($class->getTerm(), $cd, $ct)){
			$matched = $cd * 10 + $ct;
			echo $matched;
			exit;
			return false;
		}
	}
	return true;
}




function attendKnot(User $user, Knot $knot, $private) {
	$parameter = array(
			'id_student' => $user->id,
			'id_knot'    => $knot->id,
			'private'    => $private,
	);
	return $result = DB::insert($table_name = 'denpa_attend_knot', $parameter);
}





function getLecture($id, $compact = false) {
	//    if(!is_int($id))return false;
	$condition = array ('id_lecture' => $id);
	$recos = DB::getRow($table_name = 'denpa_knot_lecture', $condition, 1);
	$lecture_data = $recos[0];
	return empty($lecture_data) ? false : new Lecture($lecture_data, $compact);
}

function getClass($id, $compact = false) {
	//    if(!is_int($id))return false;
	$condition = array('id_class' => $id);
	$recos = DB::getRow($table_name = 'denpa_knot_class', $condition, 1);
	$class_data = $recos[0];
	return empty($class_data) ? false : new LClass($class_data, $compact);
}

function getKnot($id, $compact = false) {
	$condition = array('id_knot' => $id);
	$recos = DB::getRow($table_name = 'denpa_knot', $condition, 1);
	$knot_data = $recos[0];
	return empty($knot_data) ? false : new Knot($knot_data, $compact);
}


function getPsheet($id_knot, $id_student = 0) {
	$condition = array('id_knot' => $id_knot, 'id_student' => $id_student);
	$row = DB::getRow($table_name = 'denpa_psheet', $condition, $limit);
	unset($row['id_knot']);
	unset($row['id_student']);
	return empty($row) ? false : $row;
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
