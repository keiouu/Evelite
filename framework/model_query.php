<?php
/*
 * Tikapot Model Query System
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "framework/database.php");

class ModelQueryException extends Exception { }

class ModelQuery implements Iterator, Countable
{
	private $_model, $_query, $_objects, $_count, $_has_run, $_built_queries, $_position;
	
	/* $query should conform to the following structure (each line optional):
	 *  (
	 *    WHERE => (COL => Val, COL => (Val, OPER), etc),   Default: =
	 *    ORDER_BY => (COL, (COL, DESC/ASC), etc),          Default: ASC
	 *    ONLY => (COL, COL, etc),
	 *  )
	 */
	public function __construct($model, $query = array()) {
		$this->_position = 0;
		$this->_has_run = False;
		$this->_model = $model;
		$this->_query = $query;
		$this->_built_queries = array();
	}
	
	/* Allows for lazy evaluation */
	private function _ensure_run() {
		if (!$this->_has_run)
			$this->_run();
	}
	
	protected function _get_object_from_result($result) {
		$reflector = new ReflectionClass(get_class($this->_model));
		$obj = $reflector->newInstance();
		$obj->load_query_values($result);
		return $obj;
	}
	
	/* Returns the built query */
	protected function _build_query($selection = "*") {
		$query = "";
		$count = 0;
		foreach ($this->_query as $clause => $criterion) {
			$count = 0;
			foreach ($criterion as $name => $val) {
				if ($clause === "ONLY") {
					if ($count == 0)
						$selection = "($val";
					else
						$selection .= ", $val";
				} else {
					if ($count == 0)
						$query .= " $clause ";
				}
				
				if ($clause === "WHERE") {
					if ($count > 1)
						$query .= " AND "; # TODO - implement OR etc
					$query .= $name;
					if (is_array($val))
						$query .= $val[0] . $val[1];
					else
						$query .= "=" . $val;
				}
				
				if ($clause === "ORDER BY") {
					if (is_array($val))
						$query .= $val[0] . " " . $val[1];
					else
						$query .= $val . " ASC";
				}
				
				$count++;
			}
			if ($clause === "ONLY")
				$selection .= ")";
		}
		$this->_built_queries[$selection] = "SELECT $selection FROM " . $this->_model->get_table_name() . "$query;";
		return $this->_built_queries[$selection];
	}
	
	private function _get_query($selection = "*") {
		if (!isset($this->_built_queries[$selection]))
			$this->_built_queries[$selection] = $this->_build_query($selection);
		return $this->_built_queries[$selection];
	}
	
	/* Run this query */
	private function _run() {
		// Reset
		$this->_objects = array();
		$this->_count = 0;
		
		// Get objects
		$db = Database::create();
		$this->_model->create_table();
		$query = $db->query($this->_get_query());
		while($result = $db->fetch($query)) {
			array_push($this->_objects, $this->_get_object_from_result($result));
			$this->_count++;
		}
		
		$this->_has_run = True;
	}
	
	/* Returns the number of objects in this query */
	public function count() {
		if ($this->_has_run)
			return $this->_count;
		$db = Database::create();
		$query = $db->query($this->_get_query("COUNT(*)"));
		$result = $db->fetch($query);
		$this->_count = $result[0];
		return $result[0];
	}
	
	/* Returns the nth object in this query */
	public function get($n) {
		$this->_ensure_run();
		
		if ($this->count() < $n || $n < 0)
			throw new ModelQueryException("Error in get(): $n is not a valid element in this query!");
		return $this->_objects[$n];
	}
	
	/* Returns all objects in this query optionally starting at the nth element */
	public function all($n = 0) {
		$this->_ensure_run();
		
		if ($n == 0)
			return $this->_objects;
		$objects = array();
		for ($i = $n; $i < count($this->_objects); ++$i)
			array_push($objects, $this->_objects[$i]);
		return $objects;
	}
	
	/* Orders elements by (COL, (COL, DESC/ASC), etc) */
	public function order_by($query) {
		$new_query = $this->_query;
		if (is_array($query) && (count($query) != 2 || ($query[1] !== "ASC" && $query[1] !== "DESC")))
			$new_query["ORDER BY"] = $query;
		else
			$new_query["ORDER BY"] = array($query);
		return new static($this->_model, $new_query);
	}

	/* Iterator stuff */
	public function rewind() { $this->_position = 0; }
	public function current() {
		$this->_ensure_run();
		return $this->_objects[$this->_position];
	}
	public function key() { return $this->_position; }
	public function next() { ++$this->_position; }
	public function valid() {
		$this->_ensure_run();
		return isset($this->_objects[$this->_position]);
	}
	/* End of Iterator stuff */
}

?>

