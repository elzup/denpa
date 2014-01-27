<?php

class DenpaDB {
	/* @var $db DB */
	public $db;

	public function __construct() {
		$host = DSN;
		$user = DB_USER;
		$pass = DB_PASS;
		$dbnm = DB_NAME;
		$this->db = new DB($host, $user, $pass, $dbnm);
	}
}

?>