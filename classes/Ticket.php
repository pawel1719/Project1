<?php 
require_once 'Config.php';
require_once PATH_TO_DB;
require_once PATH_TO_SESSION;

class Ticket {
	private $_db;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();
	}

	public function create($fields = array()) {
		//adding data to database
		if(!$this->_db->insert('ticket', $fields)) {
			throw new Exception('#80234 There was a problem adding a ticket');
		}
	}
	
}

?> 