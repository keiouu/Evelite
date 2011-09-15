<?php
/*
 * Tikapot
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
global $home_dir;
require_once($home_dir . "lib/simpletest/autorun.php");
require_once($home_dir . "contrib/session.php");


class SessionTest extends UnitTestCase {
	function testSession() {
		Session::remove("Test");
		$this->assertEqual(Session::get("Test"), NULL);
		
		$new = Session::store("Test", 2);
		$this->assertEqual(Session::get("Test"), 2);
		$this->assertEqual($new, Session::get("Test"));
		
		$old = Session::store("Test", 5);
		$this->assertEqual(Session::get("Test"), 5);
		$this->assertEqual($old, $new);
		$no = Session::put("Test", 6);
		$this->assertEqual($no, False);
		$this->assertEqual(Session::get("Test"), 5);
		$this->assertTrue(Session::get("b43542y2") == NULL);
	}
}

?>

