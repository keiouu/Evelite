<?php
/*
 * Evelite Core App Views
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

global $home_dir;
require_once($home_dir . "framework/view.php");

class IndexView extends View
{
	public function __construct() {
		parent::__construct("/", $home_dir . "core/templates/index.php");
	}
	 
	/* Request is a 'Request' object. */
	public function render($request) {
		parent::render($request);
	}
}
?>

