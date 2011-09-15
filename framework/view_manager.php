<?php
/*
 * Tikapot View Manager
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

class ViewManager
{
	private $views;
	
	public function __construct() {
		$this->views = array();
	}
	
	public function add($view) {
		$this->views[$view->get_url()] = $view;
	}
	
	public function get($url) {
		return $this->views[$url];
	}
}

?>

