<?php

require_once('ORM_contructor.php');

class Client extends ORMConstructor {
	private $id;
	private $mission;

	/* private $orm; *//* this is implemented in the ORMConstructor class */

	public function __construct($orm, $constructor=null) {
		parrent::__construct($orm, $constructor);	
	}
}
