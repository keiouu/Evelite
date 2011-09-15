<?php
/*
 * Tikapot Model System
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "framework/database.php");
require_once($home_dir . "framework/model_query.php");
require_once($home_dir . "framework/model_fields/init.php");

class ValidationException extends Exception { }
class TableValidationException extends ValidationException { }

abstract class Model
{
	private $from_db = False, $_valid_model = False;
	protected $fields = array(), $errors = array();
	
	public function __construct() {
		$this->add_field("id", new PKField(0, $max_length = 22, True));
		$this->_valid_model = True;
	}
	
	/* Hack for get and find */
	private static function get_temp_instance() {
		$obj = new static();
		$obj->_valid_model = False;
		return $obj;
	}
	
	/* Allows custom model queries */
	protected static function get_modelquery($query = array()) {
		return new ModelQuery(new static(), $query);
	}
	
	/* Allows custom primary keys */
	protected function _pk() {
		foreach ($this->fields as $name => $field)
			if (strtolower(get_class($field)) === "pkfield")
				return $name;
	}
	
	/* Format: array("COL"=>"VAL") */
	public function load_values($array) {
		foreach ($this->fields as $name => $field)
			if (array_key_exists($name, $array))
				$field->set_value($array[$name]);
	}
	
	/* Load field values from query result. Sets "from_db" to True */
	public function load_query_values($result) {
		$this->load_values($result);
		$this->from_db = True;
	}
	
	// Allows access to stored models
	// Returns all objects
	public static function all() {
		return static::get_modelquery();
	}
	
	// Allows access to stored models
	// Returns a modelquery object containing the elements
	// $query should be in the following format: (COL => Val, COL => (OPER => Val), etc)
	public static function find($query) {
		if (!is_array($query))
			throw new ValidationException("Model::find argument must be an array!");
		$parsed_query = array();
		$db = Database::create();
		$tempobj = static::get_temp_instance();
		$tempobj->create_table();
		foreach ($query as $col => $val) {
			if (!array_key_exists($col, $tempobj->fields))
				throw new ValidationException("Model::find keys must be valid field names!");
			$parsed_query[$col] = $tempobj->fields[$col]->sql_value($db, $val);
		}
		return static::get_modelquery(array("WHERE" => $parsed_query));
	}
	
	// Allows access to stored models
	// Returns a single object
	// Errors if multiple objects are found or no objects are found
	// Arg can be an id or an array with multiple parameters
	public static function get($arg = 0) {
		$results = NULL;
    		if (is_array($arg))
			$results = static::find($arg);
		else {
			$tempobj = static::get_temp_instance();
			$results = static::find(array($tempobj->_pk() => $arg));
		}
		if ($results->count() == 0)
			throw new ModelQueryException("No objects matching query exist");
		if ($results->count() > 1)
			throw new ModelQueryException("Multiple objects matching query exist in get()");
		return $results->get(0);
	}

	// Allows access to stored models
	// Arg can be an id or an array with multiple search parameters
	// Returns an array containing:  (a single object [creates it if needed], a boolean specifying weather or not the object is a new object)
	public static function get_or_create($arg = 0) {
		$obj = NULL;
		$created = False;
		try {
			$obj = static::get($arg);
		}
		catch (ModelQueryException $e) {
			$obj = new static();
			$obj->load_values($arg);
			$obj->save();
			$created = True;
		}
		return array($obj, $created);
	}
	
	// Add a new field
	protected function add_field($name, $type) {
		if (strtolower(get_class($type)) === "pkfield") {
			$new_fields = array();
			$new_fields[$name] = $type;
			foreach ($this->fields as $name => $field)
				if (strtolower(get_class($field)) !== "pkfield")
					$new_fields[$name] = $field;
			$this->fields = $new_fields;
		} else {
			$this->fields[$name] = $type;
		}
	}
	
	public function get_table_name() {
		return strtolower(get_class($this));
	}
	
	// Get fields
	public function get_fields() {
		return $this->fields;
	}
	
	public function __get($name) {
		if ($name == "pk")
			$name = $this->_pk();
		if (isset($this->fields[$name]))
			return $this->fields[$name]->get_value();
		throw new Exception("Invalid model field '$name'.");
	}
	
	public function __set($name, $value) {
		if ($name == "pk")
			$name = $this->_pk();
		if (isset($this->fields[$name]))
			$this->fields[$name]->set_value($value);
		else
			throw new Exception("Invalid model field '$name'.");
	}
	
	// Basically: Is $name a valid field name? (Doesnt say if the field has been set)
	public function __isset($name) {
		if ($name == "pk")
			return True;
		return isset($this->fields[$name]);
	}
	
	// Unsetting a field resets it to default value
	public function __unset($name) {
		if ($name == "pk")
			return;
		if ($this->__isset($name))
			$this->fields[$name]->reset();
	}
	
	// Returns the query to create the table in the database
	public function db_create_query($db) {
		$table_name = $this->get_table_name();
		$post_scripts = "";
		$SQL = "CREATE TABLE " . $table_name . " (";
		$i = 0;
		foreach ($this->get_fields() as $name => $field) {
			if ($i > 0) $SQL .= ", ";
			$SQL .= $field->db_create_query($db, $name, $table_name);
			$i++;
			$post_query = $field->db_post_create_query($db, $name, $table_name);
			if (strlen($post_scripts) > 0 && strlen($post_query) > 0)
				$post_scripts .= ", ";
			if (strlen($post_query) > 0)
				$post_scripts .= $post_query;
		}
		if (strlen($post_scripts) > 0)
			$SQL .= ", " . $post_scripts;
		$SQL .= ");";
		
		return $SQL;
	}
	
	public function db_create_extra_queries_pre($db, $table_name) {
		$extra_scripts = array();
		foreach ($this->get_fields() as $name => $field) {
			$query = $field->db_extra_create_query_pre($db, $name, $table_name);
			if (strlen($query) > 0)
				array_push($extra_scripts, $query);
		}
		return $extra_scripts;
	}
	
	public function db_create_extra_queries_post($db, $table_name) {
		$extra_scripts = array();
		foreach ($this->get_fields() as $name => $field) {
			$query = $field->db_extra_create_query_post($db, $name, $table_name);
			if (strlen($query) > 0)
				array_push($extra_scripts, $query);
		}
		return $extra_scripts;
	}
	
	// Creates the table in the database if needed
	public function create_table() {
		$db = Database::create();
		$table_name = $this->get_table_name();
		if (!in_array($this->get_table_name(), $db->get_tables())) {
			foreach($this->db_create_extra_queries_pre($db, $table_name) as $query)
				$db->query($query);
			$res = $db->query($this->db_create_query($db));
			foreach($this->db_create_extra_queries_post($db, $table_name) as $query)
				$db->query($query);
			return $res;
		}
		return True;
	}
	
	// Verifies that the table structure in the database is up-to-date
	// NOTE: Currently only detects field name changes, not type changes
	public function verify_table() {
		$this->create_table();
		$db = Database::create();
		$table_name = $this->get_table_name();
		$fields = $this->get_fields();
		$columns = $db->get_columns($this->get_table_name());
		foreach ($columns as $column => $type) {
			if (!array_key_exists($column, $fields))
				throw new TableValidationException($column . " is no longer a part of " . $table_name);
		}
		foreach ($fields as $field => $type) {
			if (!array_key_exists($field, $columns))
				throw new TableValidationException($field . " should be in " . $table_name);
		}
		return True;
	}
	
	// Validates the model
	public function validate() {
		$this->errors = array();
		foreach ($this->get_fields() as $field_name => $field) {
			if (!$field->validate()) {
				$this->errors = array_merge($this->errors, $field->errors);
				return False;
			}
		}
		return True;
	}

	// Provides validation errors
	public function get_errors() {
		return $this->errors;
	}
	
	public function get_error_string() {
		$str = "";
		foreach ($this->get_errors() as $error) {
			if (strlen($str) > 0)
				$str .= "\n";
			$str .= $error;
		}
		return $str;
	}
	
	// Insert the object to the database
	public function insert_query($db) {
		$keys = "";
		$values = "";
		foreach ($this->get_fields() as $field_name => $field) {
			if ($field->hide_from_query)
				continue;
			if (strlen($keys) > 0) {
				$keys .= ", ";
				$values .= ", ";
			}
			$keys .= $field_name;
			$val = $field->sql_value($db);
			if (strlen($val) <= 0)
				$val = "''";
			$values .= $val;
		}
		$extra = "";
		if ($db->get_type() == "psql")
			$extra = " RETURNING " . $this->_pk();
		return "INSERT INTO " . $this->get_table_name() . " (" . $keys . ") VALUES (" . $values . ")" . $extra . ";";
	}
	
	// Insert the object to the database
	public function update_query($db) {
		$old_object = static::get($this->pk);
		$query = "UPDATE " . $this->get_table_name() . " SET ";
		$go = False;
		foreach ($old_object->get_fields() as $name => $field) {
			$new_val = $this->fields[$name];
			if (strval($field->get_value()) !== strval($new_val->get_value())) {
				if ($go)
					$query .= ", ";
				$query .= $name . "=" . $new_val->sql_value($db);
				$go = True;
			}
		}
		$query .= " WHERE " . $this->_pk() . "=" . $this->pk;
		if ($go)
			return $query;
		return ""; // Nothing to do
	}
	
	// Saves the object to the database, returns ID
	public function save() {
		if (!$this->_valid_model)
			throw new ValidationException("Error in save(): Model is not supposed to exist! (Perhaps you forgot to call parent constructor?) in " . get_class($this));
		if (!$this->validate())
			throw new ValidationException("Error in " . get_class($this) . "::save(): model did not validate! <br />" . $this->get_error_string());
		$this->create_table();
		$db = Database::create();
		$query = "";
		if (!$this->from_db) {
			$query = $db->query($this->insert_query($db));
			$id = 0;
			if ($db->get_type() == "psql") {
				$row = $db->fetch($query);
				$id = $row[0];
			}
			if ($db->get_type() == "mysql")
				$id = mysql_insert_id();
			$this->pk = intval($id);
			$this->from_db = True;
		}
		else {
			$query = $this->update_query($db);
			if (strlen($query) > 0)
				$db->query($query);
		}
		return $this->pk;
	}

	public function delete_query($db) {
		return "DELETE FROM " . $this->get_table_name() . " WHERE ". $this->_pk() ."='" . $this->pk . "';";
	}

	/* Returns True on success, False on failure */
	public function delete() {
		if (!$this->from_db)
			return False;
		$db = Database::create();
		$db->query($this->delete_query($db));
	}
}

?>

