<?php
/*
 * Tikapot Foreign Key Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/model_fields/modelfield.php");
require_once(home_dir . "framework/utils.php");

class FKValidationException extends Exception { }

class FKField extends ModelField
{
	protected static $db_type = "varchar"; // Beacuse the FK could be any type :(
	private $_class, $_recurse_check;
	
	public function __construct($model) {
		parent::__construct();
		$this->_class = Null;
		$this->update($model);
		$this->_recurse_check = false;
	}
	
	public function set_value($value) {
		parent::set_value($value);
		$this->_class = $this->_class->get($value);
	}
	
	public function get_value() {
		return $this;
	}
	
	public function get_db_type() {
		$db_type = static::$db_type;
		if ($this->_class)
			$db_type = $this->_class->get_field("pk")->get_db_type();
		return $db_type;
	}
	
	private function grab_object($class) {
		if ($this->value != "")
			$this->_class = call_user_func(array($class, 'get'), array("pk" => $this->value));
		else
			$this->_class = new $class();
	}
	
	private function update($model) {
		/*
		 * Class is in the format: appname.modelName
		 * We must scan app paths for the app, then import models.py.
		 * Hopefully, modelName willl then exist
		 */
		list($app, $n, $class) = partition($model, '.');
		if (!class_exists($class)) {
			global $app_paths;
			foreach ($app_paths as $app_path) {
				$path = home_dir . $app_path . '/' . $app . "/models.php";
				if (is_file($path)) {
					include($path);
					break;
				}
			}
		}
		if (class_exists($class))
			return $this->grab_object($class);
		throw new FKValidationException("Error: '" . $model . "' was not found!");
	}
	
	/* This recieves pre-save signal from it's model. */
	public function pre_save($model, $update) {
		// Save our model and set this db value to it's ID
		// Recurse check is for ensuring we only save if we have set a value on it,
		// That way we can have models that fk themselves.
		if ($this->_class && $this->_recurse_check)
			$this->value = $this->_class->save();
	}
	
	public function __get($name) {
		if (isset($this->_class->$name))
			return $this->_class->$name;
	}
	
	public function __set($name, $value) {
		$this->_recurse_check = true;
		if (isset($this->_class->$name))
			$this->_class->$name = $value;
	}
	
	public function __call($name, $args) {
		if(method_exists($this->_class, $name)) {
			$this->_recurse_check = true;
			call_user_func_array(array($this->_class, $name), $args);
		}
	}
	
	public function __isset($name) {
		return isset($this->_class->$name);
	}
	
	public function __unset($name) {
		unset($this->_class->$name);
		$this->_recurse_check = true;
	}
	
	public function validate() {
		return $this->_class != Null;
	}
}

?>

