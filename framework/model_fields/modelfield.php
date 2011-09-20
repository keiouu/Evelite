<?php
/*
 * Tikapot Model Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
class FieldValidationException extends Exception { }

abstract class ModelField
{
	protected static $db_type = "unknown";
	protected $default_value = "", $value = "";
	public $errors = array(), $_extra = "", $hide_from_query = False;

	public function __construct($default = "", $_extra = "") {
			$this->default_value = $default;
			$this->value = $this->default_value;
			$this->_extra = $_extra;
	}
	
	public function set_value($value) {
		$this->value = $value;
	}
	
	public function get_value() {
		return $this->value;
	}
	
	public function get_db_type() {
		return static::$db_type;
	}
	
	public function sql_value($db, $val = NULL) {
		$val = ($val == NULL) ? $this->value : $val;
		return (strlen("" . $val)  > 0) ? $val : "NULL";
	}

	public function get_default() {
		return $this->default_value;
	}
	
	public function reset() {
		$this->value = $this->default_value;
	}
	
	public abstract function validate();

	public function db_create_query($db, $name, $table_name) {
		return $name . " " . $this->get_db_type();
	}
	
	/* This allows subclasses to provide end-of-statement additions such as constraints */
	public function db_post_create_query($db, $name, $table_name) {
		return "";
	}
	
	/* This allows subclasses to provide extra, separate queries on createdb such as sequences. These are put before the create table query. */
	public function db_extra_create_query_pre($db, $name, $table_name) {
		return "";
	}
	
	/* This allows subclasses to provide extra, separate queries on createdb such as sequences. These are put after the create table query. */
	public function db_extra_create_query_post($db, $name, $table_name) {
		return "";
	}
	
	/* This recieves pre-save signal from it's model. */
	public function pre_save($model, $update) {}
}

?>

