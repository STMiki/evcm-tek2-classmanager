<?php

require_once('ORM_constructor.php');

class Zoho extends ORMConstructor {

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
