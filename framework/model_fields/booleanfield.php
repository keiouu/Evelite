<?php
/*
 * Tikapot Boolean Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once("modelfield.php");

class BooleanField extends ModelField
{
	protected static $db_type = "boolean";
	
	public function __construct($default = "False") {
		parent::__construct($default);
	}
	
	public function get_value() {
		$val = strtolower($this->value);
		return ($val == 'true' || $val == "t" || $val == "1");
	}
	
	public function sql_value($db, $val = NULL) {
		$val = ($val == NULL) ? $this->value : $val;
		if (strlen($val) == 0)
			return $this->default_value;
		$val = strtolower($val);
		$val = ($val == 'true' || $val == "t" || $val == "1");
		return ($val) ? "true" : "false";
	}

	public function validate() {
		$valid = $this->value === true || $this->value === false;
		if (!$valid)
			array_push($this->errors, "Error: Boolean did not validate: " . $this->value);
		return $valid;
	}
}

?>

