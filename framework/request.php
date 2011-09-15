<?php
/*
 * Tikapot Request Class
 *
 * Copyright 2011, AUTHORS.txt
 * Licensed under the GNU General Public License version 3.
 * See LICENSE.txt
 */

/* Basically looks prettier than full caps arrays everywhere, utility functions can be put here */
class Request
{
	private $_pagetimer;
	public $method, $page, $get, $post, $cookies;
	
	public function __construct($pagetimer = NULL) {
		// Constructs a Request object out of what we know
		$this->method = "GET";
		if (count($_POST) > 0)
			$this->method = "POST";
		$this->get = $_GET;
		$this->post = $_POST;
		$this->cookies = $_COOKIE;
		$this->page = "/";
		$this->_pagetimer = $pagetimer;
		if (isset($this->get['page'])) {
			$this->page = $this->get['page'];
			// Ensure the page has a trailing slash
			if ($this->page[strlen($this->page)-1] !== '/')
				$this->page .= '/';
		}
	}
	
	public function get_page_load_time() {
		if (isset($this->_pagetimer))
			return $this->_pagetimer->ping();
		return False;
	}
}

?>

