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
	public $method, $page, $get, $post, $cookies, $mimeType;
	
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
		if (isset($this->get['page']))
			$this->page = $this->get['page'];
		$this->mimeType = $this->get_mime_type($this->page);
	}
	
	// TODO - replace with 'finfo_open(FILEINFO_MIME_TYPE)' when
	// 5.3 becomes more widely avaliable on shared hosting
	function get_mime_type($filename) { 
		$fileext = substr(strrchr($filename, '.'), 1); 
		if (empty($fileext)) return false; 
		$regex = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($fileext\s)/i"; 
		$lines = file("mime.types"); 
		foreach($lines as $line) { 
			if (substr($line, 0, 1) == '#') continue;
			$line = rtrim($line) . " "; 
			if (!preg_match($regex, $line, $matches)) continue;
			return $matches[1]; 
		} 
		return false;
	} 

	public function get_page_load_time() {
		if (isset($this->_pagetimer))
			return $this->_pagetimer->ping();
		return false;
	}
}

?>

