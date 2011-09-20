<?php
/*
 * Tikapot MySQL Database Extension Class
 * v1.0
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/database.php");

class MySQL extends Database
{
	private $_dbname;

	protected function connect() {
		global 	$database_host,
					$database_name,
					$database_username,
					$database_password;
		$this->_dbname = $database_name;
		$this->_link = mysql_connect($database_host, $database_username, $database_password, true);
		if ($this->_link)
			$this->_connected = mysql_select_db($database_name, $this->_link);
		else
			throw new NotConnectedException("Error: Could not connect to the database server.");
	}
	
	public function query($query, $args=array()) {
		if (!$this->_connected) {
			throw new NotConnectedException("Error: the database is not connected!");
		}
		$vars = array();
		foreach ($args as $arg)
			array_push($vars, mysql_real_escape_string($arg));
		$query = sprintf($query, $vars);
		$res = mysql_query($query, $this->_link);
		if (strpos($query, "ATE TABLE") > 0 || strpos($query, "OP TABLE") > 0)
			$this->populate_tables();
		return $res;
	}
	
	public function fetch($result) {
		if (!$this->_connected) {
			throw new NotConnectedException("Error: the database is not connected!");
		}
		return mysql_fetch_array($result, MYSQL_BOTH);
	}
	
	public function disconnect() {
		if ($this->_connected) {
			$this->_connected = !mysql_close($this->_link);
		}
	}
	
	public function populate_tables() {
		$this->_tables = array();
		$query = $this->query("SHOW TABLES;");
		while($result = $this->fetch($query))
			array_push($this->_tables, $result[0]);
	}

	/* Returns a query */
	public function get_columns($table) {
		$arr = array();
		$query = $this->query("SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_NAME='" . $table . "' AND TABLE_SCHEMA='" . $this->_dbname . "';");
		while($col = $this->fetch($query))
			$arr[$col["COLUMN_NAME"]] = $col["DATA_TYPE"];
		return $arr;
	}
}

?>

