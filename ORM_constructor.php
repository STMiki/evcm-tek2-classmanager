<?php

require_once('exception.php');

/*
 * ORMConstructor
 *
 * this class is used to implement the orm in the child class
 * so I don't need to implement it to all class.
 *
 */
class ORMConstructor {
	private $orm;
	private $_status;
	private $_error;
	private $_errorType;

	public function __construct($orm, $constructor=null) {
		$this->orm = $orm;
		$this->orm->used += 1;
		$this->_status = 0;
		$this->_error = '';

		if ($constructor != null) {
			try {
				($this->$constructor)();
			} catch (Exception $e) {
				printLog(__METHOD__, $e->getMessage());
			}
		}
	}
	
	public function __destruct() {
		$this->orm->used -= 1;
		if ($this->orm->used <= 0) {
			$this->orm->__destruct();
		}
	}

	private function setError($errorType, $errorMessage) {
		$this->_status = 1;
		$this->_errorType = $errorType;
	}

	public function raiseError() {
		if ($this->_status != 0) {
			$error = __CLASS__.'Exception';
			throw new $error($this->_error);
		}
	}
}
