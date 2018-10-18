<?php

/*
 * ORM.php
 *
 * ORM (Object Relational Mapping)
 * https://en.wikipedia.org/wiki/Object-relational_mapping
 *
 * I will use this for load and create my class.
 * All class (prestation, mission, helper, etc...) will be
 * created using this ORM
 *
 * The database will be created in the ORM, so if you ever
 * need the db, you can get it from here.
 *
 */

/* -------------------------------------------- 
 * ---  Load automaticaly the class needed  ---
 * --- The file need to be like 'Class.php' ---
 * --------------------------------------------
 */
function classAutoLoad($class) {
	require($class.'.php');
}

spl_autoload_register('classAutoLoad');

/* ---- End autoloading class function ---- */

require_once('log.php');

/*
 * class ORM
 *
 * This class is used for loading for communicate to the database.
 * /!\ WARNING :!\
 * I use Mission and Prestation
 * this is not the same.
 * a Mission is the act where a helper go to the Client need it.
 * a Prestation is the thing that the Client need the helper for.
 * 	(windows 10 blue screen, install printer, etc...)
 *
 */
class ORM {
	private $db;
	private $used;

	public function __construct() {
		$this->db = $Database()->getConnection();
		$this->used = 0;
	}

	public function getDatabase() {
		return ($this->db);
	}

	/* ---- Helper related function ---- */
	public function getHelperById($id) {

	}

	/* ---- Client related function ---- */
	public function getClientById($id) {

	}

	/* ---- Mission related function ---- */
	public function getMissionById($id) {
		
	}

	/* ---- Prestation related function ---- */
	private function getPrestationById($id) {
		$mission = new Mission($this, 'fillById');
		if ($mission->getStatus() == 0)
			return ($mission);
		unset($mission);
		return (null):
	}
}

