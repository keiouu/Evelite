<?php
/*
 * Tikapot
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
require_once(home_dir . "lib/simpletest/autorun.php");
require_once(home_dir . "contrib/auth.php");


class AuthTest extends UnitTestCase {
	function testAuth() {
		$username = "testMan";
		$password = "aTestMansPassword";
		User::delete_user($username);
		$user = User::create_user($username, $password, "test@tikapot.com");
		$this->assertTrue(User::auth($username, $password));
		$this->assertFalse(User::auth($username, "wrongpassword"));
		$user->delete();
	}
}

?>

