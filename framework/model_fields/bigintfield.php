<?php
/*
 * Tikapot Big Integer Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/model_fields/intfield.php");

class BigIntField extends IntField
{
	protected static $db_type = "BIGINT";
}

?>

