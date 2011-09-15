<?php
/*
 * Tikapot Primary Key Field
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once("bigintfield.php");

class PKField extends BigIntField
{
	public function db_create_query($db, $name, $table_name) {
		$val = parent::db_create_query($db, $name, $table_name);
		if ($db->get_type() == "mysql")
			$val .= " PRIMARY KEY";
		return $val;
	}
	
	/* This allows subclasses to provide end-of-statement additions such as constraints */
	public function db_post_create_query($db, $name, $table_name) {
		if ($db->get_type() == "psql")
			return "CONSTRAINT ".$table_name."_pkey PRIMARY KEY (".$name.")";
	}
}

?>

