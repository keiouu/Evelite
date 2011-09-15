<?php
/*
 * Tikapot DateTime Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once("modelfield.php");
require_once("datefield.php");

class DateTimeField extends DateField
{
	protected static $db_type = "timestamp";
	
	public function validate() {
		if (strlen($this->value) == 0)
			return True;
		$regex = "/^(\d{4})(-)(\d{2})(-)(\d{2})\x20(\d{2})(:)(\d{2})(:)(\d{2})$/";
		$valid = preg_match($regex, $this->value) == 1;
		if (!$valid)
			array_push($this->errors, "Error: DateTime is not in the format: YYYY-MM-DD HH:MM:SS");
		return $valid;
	}
}

?>

