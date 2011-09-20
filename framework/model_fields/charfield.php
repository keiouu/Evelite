<?php
/*
 * Tikapot Char Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/model_fields/modelfield.php");

class CharField extends ModelField
{
	protected static $db_type = "VARCHAR";
	private $max_length = 0;
	
	public function __construct($max_length = 0, $default = "", $_extra = "") {
			parent::__construct($default, $_extra);
			$this->max_length = $max_length;
	}
	
	public function sql_value($db, $val = NULL) {
		$val = ($val == NULL) ? $this->value : $val;
		if (strlen($val) <= 0)
			return '';
		return "'" . $val . "'";
	}
	
	public function validate() {
		if ($this->max_length > 0 && strlen($this->value) > $this->max_length) {
			array_push($this->errors, "Value is longer than max_length");
			return False;
		}
		return True;
	}
	
	public function db_create_query($db, $name, $table_name) {
		$extra = "";
		if ($this->max_length > 0)
			$extra .= " (" . $this->max_length . ")";
		if (strlen($this->default_value) > 0)
			$extra .= " DEFAULT '" . $this->default_value . "'";
		if (strlen($this->_extra) > 0)
			$extra .= ' ' . $this->_extra;
		return $name . " " . $this::$db_type . $extra;
	}
}

?>
