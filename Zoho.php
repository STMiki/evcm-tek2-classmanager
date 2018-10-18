<?php

require_once('log.php');

class Zoho {

	private $domain;

	public $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function __destruct() {
		//
	}

	private function getCleApi() {
		
	}
}
