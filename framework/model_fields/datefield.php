<?php
/*
 * Tikapot Date Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/model_fields/modelfield.php");

class DateField extends ModelField
{
	public static $FORMAT = "Y-m-d";
	protected static $db_type = "date";
	private $auto_now_add = False, $auto_now = False;
	
	public function __construct($auto_now_add = False, $auto_now = False, $default = "", $_extra = "") {
			parent::__construct($default, $_extra);
			$this->auto_now_add = $auto_now_add;
			$this->auto_now = $auto_now;
	}
	
	/* This recieves pre-save signal from it's model. */
	public function pre_save($model, $update) {
		if ($this->auto_now || (!$update && $this->auto_now_add))
			$this->value = date(static::$FORMAT, time());
	}
	
	public function sql_value($db, $val = NULL) {
		$val = ($val == NULL) ? $this->value : $val;
		return (strlen($val) > 0) ? "'" . $val . "'" : "NULL";
	}
	
	public function validate() {
		if (strlen($this->value) == 0)
			return True;
		$regex = "/^(\d{4})(-)(\d{2})(-)(\d{2})$/";
		$valid = preg_match($regex, $this->value) == 1;
		if (!$valid)
			array_push($this->errors, "Error: Date is not in the format: YYYY-MM-DD");
		return $valid;
	}
}

?>

