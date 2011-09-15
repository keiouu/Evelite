<?php
/*
 * Tikapot View System
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

class View
{	
	/* 
	 * What is a view?
	 * A view links a URL to a php function
	 * This php function renders a page
	 */
	protected $url, $page;
	public function __construct($url, $page = "") {
		$this->set_url($url);
		$this->page = $page;
		
		global $view_manager;
		$view_manager->add($this);
	}
	
	public function set_url($url) {
		$this->url = $url;
	}
	public function get_url() {
		return $this->url;
	}
	 
	/* Request is a 'Request' object. By default this simply includes $this->page be sure to override for more complex things! */
	public function render($request) {
		include($this->page);
	}
}

?>

