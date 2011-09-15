<?php
/*
 * Tikapot Application Loader
 * v1.0
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */
 
function load_applications() {
	global $app_paths, $apps_list, $home_dir;
	foreach ($apps_list as $app) {
		foreach ($app_paths as $app_path) {
			$filename = $home_dir . $app_path . "/" . $app . "/init.php";
			if (file_exists($filename)) {
				include($filename);
				break;
			}
		}
	}
}
?>
