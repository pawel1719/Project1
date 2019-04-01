<?php
require_once 'config.php';

class DB {
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;
		
	private function __construct() {
		//used class PDO to connection
		try{
			$this->_pdo = new PDO('mysql:host='. $GLOBALS['connect']['host'] .';dbname='. $GLOBALS['connect']['name_db'] .';port='. $GLOBALS['connect']['port'] .';charset='. $GLOBALS['connect']['encoding'], $GLOBALS['connect']['user'], $GLOBALS['connect']['password'] );
			
		}catch(PDOException $e){
			//error conection
			die('Connection faild: '. $e->getMessage());
		}
	}//end  __construct
	
	public static function getInstance() {
		//use yourself instance if it exist 
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}//end function getInstance()
	
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($params)) {
				foreach($params as $parm) {
					$this->_query->bindValue($x, $parm);
					$x++;
				}
			}
			
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		
		return $this;
	}//end function query()
	
	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '<', '>', '>=', '<=', '!=');
			
			//field inside query
			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];
			
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				//query on database
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		
		return false;
	}//end function action()2
	
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}//end function get
	
	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}//end function delete()
	
	public function insert($table, $fields = array()) {
		$key = array_keys($fields);
		$value = '';
		$x = 1;
		
		foreach($fields as $field) {
			$value .= '?';
			if($x < count($fields)) {
				$value .= ', ';
			}
			$x++;
		}
		//query
		$sql = "INSERT INTO {$table} (`". implode('`, `', $key) ."`) VALUES ({$value})";
		
		//update on database
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		
		return false;
	}//end function INSERT()
	
	public function update($table, $id, $fields = array()) {
		$set = '';
		$x = 1;
		
		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		//query
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		//update on database
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		
		return false;
	}//end function UPDATE()

	public function toJSON($table, $where  = array('ID', '>', '0'), $select = 'SELECT *') {
		$query = $this->action( $select, $table, $where);

		//convert data from table to JSON
		return json_encode($query->_results);
	}
	
	public function results() {
		return $this->_results;
	}//end function results()
	
	public function first() {
		$this->results()[0];
	}//end function first
	
	public function error() {
		return $this->_error;
	}//end function error()
	
	public function count() {
		return $this->_count;
	}//end function count()
	
}//end class