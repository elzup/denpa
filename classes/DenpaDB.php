<?php

require_once(DIR_CLASS. 'DataBase.php');

class DenpaDB {
	/* @var $db DB */
	public static $db;

	public static function start() {
		$host = DSN;
		$user = DB_USER;
		$pass = DB_PASS;
		$dbnm = DB_NAME;
		DenpaDB::$db = new DB($host, $user, $pass, $dbnm);
	}

	public static function end() {
		DenpaDB::$db->close();
	}


	/* --------------------------------------------------------- *
	 *     methods
	* --------------------------------------------------------- */

	public static function registUserFirst($parameter){
		$parameter['id'] = convertIdTonum($id);
		$parameter['password'] = hashing($parameter['password']);
		$reg_id = DenpaDB::$db->getMinEmptyId("denpa_user", "number");
		$parameter['number'] = $reg_id;
		$parameter['mail'] = 'NULL';
		$parameter['point'] = '10000';
		$parameter['time_register'] = 'NOW()';
		$parameter['register_process'] = 'NOW()';
		$result = DenpaDB::$db->insert('denpa_user', $parameter);
	}

	public static function registTempUser($name, $reg_key){
		$id =convertIdToNum($name);
		$parameter = array('time_register' => 'NOW()', 'ticket' => $reg_key);
		if(isRegisteredTempUser($name) == 1){
			$condition = array('id' => $id);
			$result = DenpaDB::$db->update($table_name = 'denpa_user_temp', $parameter, $condition, 1);
		}
		else{
			$result = DenpaDB::$db->insert($table_name = 'denpa_user_temp', $parameter);
		}
		return !!$result;
	}

	public static function getUser($name , $password='', $shaed=false, $compact = false) {
		if($password == '') {
			$idTemp = convertIdToNum($name);
			$recos = DenpaDB::$db->getTable('denpa_user', null, array('id' => $idTemp), 1);
			$user_data = $recos[0];
		} else {
			if(!$shaed)$password = hashing($password);
			$recos = array();
			if(isTypeId($name)){
				$idTemp=convertIdToNum($name);
				$recos = DenpaDB::$db->getTable('denpa_user', null, array('id' => $idTemp, 'password' => $password), 1);
			}
			else{
				$recos = DenpaDB::$db->getTable('denpa_user', null, array('id_screen' => $name, 'password' => $password), 1);
				//            $sql = "select * from denpa_user where id_screen= $name and password= $password limit 1";
			}
			$user_data = $recos[0];
		}
		if($user_data['register_process'] == 'a' && !$shaed){
			jump("signup_second?id=".convertIdToStr($user_data['id'])."&key=".$user['password']);
		}
		return empty($user_data) ? false : new User($user_data, $compact);
	}

	public static function isRegisteredTempUser($id){
		$idTemp = convertIdToNum($id);
		$condition = array('id' => $idTemp);
		$recos = DenpaDB::$db->getTable($table_name = 'denpa_user_temp', null, $condition, 1);
		$user = $recos[0];
		if(!$user)return false;
		$registTimer=strtotime($user['time_register']);
		if(time()<strtotime("+2 day", $registTimer)) {
			return $user;
		}else{
			$result = DenpaDB::$db->delete($table_name = 'denpa_user_temp', $condition);
			return 1;
		}
	}

	public static function registKnot($parameter, $psheet_param) {
		foreach ($parameter as &$p) $p = is_sql($p);
		$reg_id = DenpaDB::$db->getMinEmptyId("denpa_knot", "id_knot");
		$parameter['id_knot'] = $reg_id;
		$result = DenpaDB::$db->insert($table_name = 'denpa_knot', $parameter);
		if($result) {
			$reg_id = $table_data['Auto_increment'] - 1;
			if(!empty($psheet_param['param_1'])) {
				$result3 = registPsheet($registed_id, $psheet_param);
			}
			return $reg_id;
		}
		return false;
	}

	public static function updateKnot(Knot $knot, $parameter, $psheet_param) {
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
			DenpaDB::$db->update($table_name = 'denpa_knot', $parameter, $condition, 1);
		}
		if(!empty($psheet_param))updatePsheet($knot, $psheet_param);
		return (empty($err)) ? true: $err;
	}


	public static function registPsheet($id_knot, $parameter) {
		return $result = DenpaDB::$db->insert($table_name = 'denpa_psheet', $parameter);
	}

	public static function updatePsheet(Knot $knot, $parameter) {
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
			$result = DenpaDB::$db->update($table_name = 'denpa_psheet', $parameter, $condition);
			return $result;
		}
		return false;
	}

	public static function registLecture($parameter) {
		$reg_id = DenpaDB::$db->getMinEmptyId("denpa_knot_lecture", "id_lecture");
		$parameter['id_lecture'] = $reg_id;
		//    print_r($parameter);
		//    exit;
		$result = DenpaDB::$db->insert($table_name = 'denpa_knot_lecture', $parameter);
		return empty($result) ? false : $reg_id;
	}

	public static function updateLecture (Lecture $lecture, $parameter) {
		$err = null;
		$parameter_pre = $lecture->getArrayParams();
		foreach($parameter_pre as $key => $value_pre) {
			$value = $parameter[$key];
			if(empty($value) || $value_pre == $value)
				unset($parameter[$key]);
		}
		if(!empty($parameter)) {
			$condition = array('id_lecture' => $lecture->id);
			$result = DenpaDB::$db->update($table_name = 'denpa_knot_lecture', $parameter, $condition);
		} else {
			$err = "変更点がありません";
		}
		return (empty($err)) ? true: $err;
	}

	public static function registClass($parameter) {
		foreach ($parameter as &$p) $p = is_sql($p);
		$reg_id = DenpaDB::$db->getMinEmptyId("denpa_knot_class", "id_class");
		$parameter['id_class'] = $reg_id;


		//     $sql = sprintf("INSERT INTO `denpa_knot_class` (`id_class`, `id_root`, `schedules`, `room`, `teacher`, `limit`, `textbook`, `measurement`, `prepare`)
		//             VALUES ('$reg_id', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
		//             $parameter['root'], $parameter['schedule'], $parameter['room'], $parameter['teacher'],
		//             $parameter['limit'], $parameter['textbook'], $parameter['measurement'], $parameter['prepare']);
		//     $result = mysql_query($sql)or die("ERROR::".mysql_error()."\n$sql");
		$result = DenpaDB::$db->insert($table_name = 'denpa_knot_class', $parameter);
		if($result) {
			return $reg_id;
		}
		return empty($result) ? false : $reg_id;
	}

	public static function updateClass (LClass $class, $parameter) {
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
			$result = DenpaDB::$db->update($table_name = 'denpa_knot_class', $parameter, $condition);
		} else {
			$err = '変更点がありません';
		}
		return (empty($err)) ? true: $err;
	}

	public static function attendClass(User $user, LClass $class) {
		$parameter = array(
				'id_student' => $user->id,
				'id_class'   => $class->id,
				//            'time_register' => 'NOW()',
		);
		return $result = DenpaDB::$db->insert($table_name = 'denpa_attend_class', $parameter);
	}

	public static function checkAttend(User $user, LClass $class, &$matched = null){
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




	public static function attendKnot(User $user, Knot $knot, $private) {
		$parameter = array(
				'id_student' => $user->id,
				'id_knot'    => $knot->id,
				'private'    => $private,
		);
		return $result = DenpaDB::$db->insert($table_name = 'denpa_attend_knot', $parameter);
	}





	public static function getLecture($id, $compact = false) {
		//    if(!is_int($id))return false;
		$condition = array ('id_lecture' => $id);
		$recos = DenpaDB::$db->getRow($table_name = 'denpa_knot_lecture', $condition, 1);
		$lecture_data = $recos[0];
		return empty($lecture_data) ? false : new Lecture($lecture_data, $compact);
	}

	public static function getClass($id, $compact = false) {
		//    if(!is_int($id))return false;
		$condition = array('id_class' => $id);
		$recos = DenpaDB::$db->getRow($table_name = 'denpa_knot_class', $condition, 1);
		$class_data = $recos[0];
		return empty($class_data) ? false : new LClass($class_data, $compact);
	}

	public static function getKnot($id, $compact = false) {
		$condition = array('id_knot' => $id);
		$recos = DenpaDB::$db->getRow($table_name = 'denpa_knot', $condition, 1);
		$knot_data = $recos[0];
		return empty($knot_data) ? false : new Knot($knot_data, $compact);
	}


	public static function getPsheet($id_knot, $id_student = 0) {
		$condition = array('id_knot' => $id_knot, 'id_student' => $id_student);
		$row = DenpaDB::$db->getRow($table_name = 'denpa_psheet', $condition, $limit);
		unset($row['id_knot']);
		unset($row['id_student']);
		return empty($row) ? false : $row;
	}
}

?>