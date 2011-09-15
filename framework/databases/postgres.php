<?php
/*
 * Tikapot PostgreSQL Database Extension Class
 * v1.0
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "framework/database.php");

class PostgreSQL extends Database
{
	private $_dbname;

	protected function connect() {
		global 	$database_host,
					$database_name,
					$database_username,
					$database_password;
		$this->_dbname = $database_name;
		$this->_link = pg_connect("host=$database_host user=$database_username password=$database_password dbname=$database_name connect_timeout=5");
		$this->_connected = isset($this->_link);
		if (!$this->_connected)
			throw new NotConnectedException("Error: Could not connect to the database server.");
	}
	
	private function throw_query_exception($e) {
		throw new QueryException($e);
	}
	
	public function query($query, $args=array()) {
		if (!$this->_connected) {
			throw new NotConnectedException("Error: the database is not connected!");
		}
		$res = pg_query_params($this->_link, $query, $args);
		if (strpos($query, "ATE TABLE") > 0 || strpos($query, "OP TABLE") > 0)
			$this->populate_tables();
		return $res;
	}
	
	public function fetch($result) {
		if (!$this->_connected) {
			throw new NotConnectedException("Error: the database is not connected!");
		}
		return pg_fetch_array($result, NULL, PGSQL_BOTH);
	}
	
	public function disconnect() {
		if ($this->_connected) {
			$this->_connected = !pg_close($this->_link);
		}
	}
	
	public function populate_tables() {
		$this->_tables = array();
		$query = $this->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
		while($result = $this->fetch($query))
			array_push($this->_tables, $result["table_name"]);
	}

	/* Returns a query */
	public function get_columns($table) {
		$arr = array();
		$query = $this->query("SELECT * from ".$table.";");
		$i = pg_num_fields($query);
		for ($j = 0; $j < $i; $j++)
			$arr[pg_field_name($query, $j)] = pg_field_type($query, $j);
		return $arr;
	}
}

?>

