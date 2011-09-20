<?php
/*
 * Tikapot
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
require_once(home_dir . "framework/model.php");
require_once(home_dir . "framework/model_fields/init.php");

class TestFKModel extends Model
{
	public function __construct() {
		parent::__construct();
		$this->add_field("test_prop", new CharField("", $max_length=7));
	}
}

?>

