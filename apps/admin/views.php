<?php
/*
 * Evelite Admin App Views
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

require_once(home_dir . "framework/view.php");

class AdminIndexView extends View
{
	public function __construct() {
		parent::__construct("/admin/", home_dir . "apps/admin/templates/index.php");
	}
	 
	/* Request is a 'Request' object. */
	public function render($request) {
		parent::render($request);
	}
}
?>

