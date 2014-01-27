<?php

class DB {
	public $link;

	private $check = false;

	public function __construct($host, $user, $password, $database) {
		$this->connectDb($host, $user, $password, $database);
	}

	public function setCheckMode($value = true) {
		$this->check = $value;
	}
	public function setCheckModeOn() {
		$this->setCheckMode();
	}
	public function setCheckModeOff() {
		$this->setCheckMode(false);
	}

	public function getError() {
		return mysql_error($this->link);
	}


	public function setCharset($encode = 'utf8') {
		mysqli_set_charset($this->link, $encode) or super_die(array(mysqli_error($this->link), $sql, __METHOD__));
	}


	public function selectDb($name_db) {
		echo "<pre>";
		print_r($this->link);
		mysqli_select_db($this->link, $name_db) or super_die(array(mysqli_error($this->link), __METHOD__, $name_db));
	}

	public function isExist($table, $column, $data, $limit = 1){
		$sql = "SELECT * FROM $table WHERE $column = '$data' LIMIT 1";
		if ($this->check) echo $sql;
		else $result = mysqli_query($this->link, $sql) or super_die(array(mysqli_error($this->link), $sql, __METHOD__));
		$row = mysqli_fetch_row($result);
		return $row[0];
	}

	public function isExistCond($table_name, $condition, $limit = 1){
		$result = $this->getTable($table_name, null, $condition, 1);
		return !empty($result);
	}

	public function getMinEmptyId($table_name, $column_name) {
		$recos = $this->getTable($table_name, $column = array($column_name));
		$nums = array();
		if(!$recos[0]) return 0;
		foreach($recos as $r)
			$nums[] = $r[$column_name];
		sort($nums);
		$i = 1;
		while (!empty($nums[$i]) && $nums[$i] == $i) $i++;
		return $i;
	}

	public function insert($table_name, $parameter) {
		$sql_head = "INSERT INTO `{$table_name}`(";
		$sql_foot = "VALUES(";
		foreach ($parameter as $key => $value ) {
			$sql_head .= sprintf("`%s`, ", DB::toSql($key));
			if($key == 'date' && preg_match('/\d{4}-\d\d-\d\d/', $value)) {
				$sql_foot .= "CAST('$value' AS DATE), ";
			}
			elseif($value == 'NOW()')
			$sql_foot .= "NOW(), ";
			else
				$sql_foot .= sprintf("'%s', ", DB::toSql($value));
		}
		$sql_head = substr($sql_head, 0, -2) . ")";
		$sql_foot = substr($sql_foot, 0, -2) . ")";
		$sql = $sql_head.$sql_foot;
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql) or super_die(array(mysqli_error($this->link), $sql, __METHOD__));
		// return $result = mysqli_query($this->link, $sql) or print(mysqli_error($this->link));
	}

	public function update($table_name, $parameter, $condition = null, $limit = null) {
		$sql = "UPDATE `{$table_name}` SET";
		foreach($parameter as $key => $value) {
			if($key == 'date' && preg_match('/\d{4}-\d\d-\d\d/', $value)) {
				$sql_foot .= "CAST('$value' AS DATE), ";
			}
			elseif($value == 'NOW()')
			$sql .= sprintf(" `%s` = NOW(),", DB::toSql($key));
			else
				$sql .= sprintf(" `%s` = '%s',", DB::toSql($key), DB::toSql($value));
		}
		$sql = substr($sql, 0, -1);
		if(!empty($condition)) {
			$sql .= " WHERE";
			foreach($condition as $key => $value) {
				$sql .= sprintf(" `%s` = '%s' AND", DB::toSql($key), DB::toSql($value));
			}
			$sql = substr($sql, 0, -3);
		}
		if(!empty($limit)) $sql .= "LIMIT $limit";
		// super_die(array(mysqli_error($this->link), $sql, __METHOD__));
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__));
	}




	public function query($query, $table_name, $column, $condition, $limit) {
	}


	public function getTable($table_name, $column = null, $condition = null, $limit = null, $orderBy = null, $desc = false) {
		$sql = 'SELECT';
		if(!empty($column)) {
			foreach($column as $value) {
				$sql .= sprintf(" %s,", $value);
			}
			$sql = substr($sql, 0, -1);
		} else $sql .= " *";
		$sql .= " FROM `$table_name`";
		if(!empty($condition)) {
			$sql .= " WHERE";
			foreach($condition as $key => $value)
				$sql .= sprintf(" `%s` = '%s' AND", DB::toSql($key), DB::toSql($value));
			$sql = substr($sql, 0, -3);
		}
		if(!empty($orderBy)) $sql .= "ORDER BY `$orderBy` " . ($desc ? "DESC " : "ASC ");
		if(!empty($limit)) $sql .= "LIMIT $limit";

		if ($this->check) echo $sql;
		else $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
		$datas = array();
		// while($rec = mysqli_fetch_assoc($result)) {
		while($rec = mysqli_fetch_assoc($result)) {
			$datas[] = $rec;
		}
		return $datas;
	}

	public function delete($table_name, $condition, $limit) {
		if(empty($condition)) return false; //念のため
		$sql = 'DELETE';
		$sql .= " FROM `$table_name`";
		$sql .= " WHERE";
		foreach($condition as $key => $value) {
			$sql .= sprintf(" `%s` = '%s' AND", DB::toSql($key), DB::toSql($value));
		}
		$sql = substr($sql, 0, -3);
		if(!empty($limit)) $sql .= "LIMIT $limit";
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
	}

	public function getRow($table_name, $condition = null, $limit = null) {
		return $this->getTable($table_name, null, $condition, $limit);
	}

	public function getRowOne($table_name, $condition = null) {
		$result = $this->getTable($table_name, null, $condition, 1);
		return $result[0];
	}

	public function getData($table_name, $column_name, $condition) {
		$rec = $this->getTable($table_name, array($column_name), $condition, 1) ;
		return $rec[0][$column_name];
	}

	public function insertAdd($table_name, $parameter, $parameter_dep) {
		$sql_head = "INSERT INTO `{$table_name}`(";
		$sql_foot = "VALUES(";
		$sql_tail = 'ON DUPLICATE KEY UPDATE ';
		foreach ($parameter as $key => $value ) {
			$sql_head .= sprintf("`%s`, ", DB::toSql($key));
			if($key == 'date' && preg_match('/\d{4}-\d\d-\d\d/', $value)) {
				$sql_foot .= "CAST('$value' AS DATE), ";
			}
			else if($value == 'NOW()')
				$sql_foot .= "NOW(), ";
			else
				$sql_foot .= sprintf("'%s', ", DB::toSql($value));
		}
		foreach ($parameter_dep as $key => $value) {
			$sql_tail .= sprintf('%s = %s + %s, ', $key, $key, $value);
		}
		$sql_head = substr($sql_head, 0, -2) . ")";
		$sql_foot = substr($sql_foot, 0, -2) . ")";
		$sql_tail = substr($sql_tail, 0, -2);
		$sql = $sql_head.$sql_foot.$sql_tail;
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
	}

	public function deleteTable($table_name) {
		$sql = 'TRUNCATE TABLE '.$table_name;
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
	}

	public function lastInsertId() {
		$sql = 'SELECT LAST_INSERT_ID()';
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
	}

	public function inclement($table_name, $column_name, $condition) {
		$sql = 'UPDATE '. $table_name;
		$sql .= ' SET ';
		$sql .= $column_name . " = " . $column_name ."+1 ";
		if (!empty($condition)) {
			$sql .= " WHERE";
			foreach($condition as $key => $value) {
				$sql .= sprintf(" `%s` = '%s' AND", DB::toSql($key), DB::toSql($value));
			}
			$sql = substr($sql, 0, -3);
		}
		if ($this->check) echo $sql;
		else return $result = mysqli_query($this->link, $sql)or super_die(array(mysqli_error($this->link), $sql, __METHOD__, $query, $result));
	}

	public static function toSql($str) {
		return mysqli_real_escape_string($this->link, $str);
	}



	// ------------------- startup -------------------//
	public function connectDb($host, $user, $password, $database){
		mb_language("uni");
		mb_internal_encoding("UTF-8");
		mb_http_input("auto");
		mb_http_output("utf-8");
		//    	super_die(array ('dsn' => DB_DSN, 'user' => DB_USER, 'pass' => DB_PASS));
		$this->link = mysqli_connect($host, $user, $password, $database);
		//    	DB::selectDb(DB_NAME);
	}

	public function close() {
		mysqli_close($this->link) or super_die(array("mysql_error" => mysqli_error($this->link), "Method" => __METHOD__));
	}
}


?>
