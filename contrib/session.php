<?php
/*
 * Tikapot Session Class
 * v1.0
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
session_start();

abstract class Session
{
	/* Store val in session under var, overwrites if necessary. Returns: Previous value if it existed, or the new value if it didnt. */
	static function store($var, $val) {
		$ret = $val;
		if (isset($_SESSION[$var]))
			$ret = $_SESSION[$var];
		$_SESSION[$var] = $val;
		return $ret;
	}
	
	/* Put the val into session under var ONLY if there is no var in session already. Returns true if successful or false if not. */
	static function put($var, $val) {
		if (isset($_SESSION[$var]))
			return False;
		Session::store($var, $val);
		return True;
	}
	
	static function get($var) {
		if (isset($_SESSION[$var]))
			return $_SESSION[$var];
		return NULL;
	}
	
	/* removes var from the session. Returns old value (or NULL if it didnt exist) */
	static function remove($var) {
		if (isset($_SESSION[$var])) {
			$ret = $_SESSION[$var];
			unset($_SESSION[$var]);
			
			// Remove key too
			$new_session = array();
			foreach ($_SESSION as $key => $value)
				if ($key !== $var)
					$new_session[$key] = $value;
			$_SESSION = $new_session;
			return $ret;
		}
		return NULL;
	}
}

?>

