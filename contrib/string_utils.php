<?php
/*
 * Tikapot String Utils Class
 * v1.0
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
function starts_with($haystack, $needle){
	return substr($haystack, 0, strlen($needle)) === $needle;
}
 
function ends_with($haystack, $needle){
	return strrpos($haystack, $needle) === strlen($haystack)-strlen($needle);
}

?>
 
