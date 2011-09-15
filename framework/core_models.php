<?php
/*
 * Tikapot Core Models
 * 
 * This file contains models that are essential for
 * the correct operation of tikapot core modules
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "framework/model.php");
require_once($home_dir . "framework/model_fields/init.php");

class Config extends Model
{	
	public function __construct() {
		parent::__construct();
		$this->add_field("key", new CharField("", 250));
		$this->add_field("value", new CharField("", 250));
	}
}

?>

