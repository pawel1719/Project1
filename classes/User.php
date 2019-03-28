<?php
require_once('DB.php');
require_once('Hash.php');
require_once('Session.php');
require_once('config.php');

class User {
	private $_db,
			$_data,
			$_sessionName,
			$_isLoggedIn;
	
	public function __construct($user = null) {
		$this->_db = DB::getInstance();
		$this->_sessionName = SESSION_NAME;

		if(!$user) {
			if(Session::exist($this->_sessionName)) {
				$user = Session::get($this->_sessionName);

				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					$this->_isLoggedIn = false;
				}
			} else {
				$this->find($user);
			}
		}
	}//end constructor

	public function update($fields = array(), $id = null) {
		if(!$id && $this->isLoggedIn()) {
			$id = $this->data()->ID;
		}

		if(!$this->_db->update('users', $id, $fields)) {
			throw new Exception('There was a problem updating!');
		}
	}//end function update
	
	public function create($fields = array()) {
		if(!$this->_db->insert('users', $fields)) {
			throw new Exception('There was a problem creating an account!');
		}
	}//end function create
	
	public function find($user = null) {
		if($user) {
			$field = (is_numeric($user)) ? 'ID' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));
			
			if($data->count()) {
				$this->_data = $data->results()[0];	
				return true;
			}
		}
		
		return false;
	}//end function find
	
	public function login($username = null, $password = null) {
		$user = $this->find($username);
		
		if($user) {
			if($this->_data->password === Hash::make($password, $this->data()->salt)) {
				Session::put($this->_sessionName, $this->data()->ID);
				return true;
			}
		}

		return false;
	}//end function login

	public function counterLogin($field, $filed_time, $username) {
		$user = $this->find($username);

		if($user) {
			try {
				$counter = $this->data()->$field;
				$counter++;
				$this->update(array(
					$field => $counter,
					$filed_time => @date('Y-m-d H:i:s')
				), $this->data()->ID);
				return true;
			}catch (Exception $e){
				echo 'Counter error'.$e->getMessage();
			}
		}

		return false;
	}//end function counterLogin

	public function passwordHistory($fields = array(), $password, $date_password, $id) {
		$user = $this->find($id);
		if($user) {
			$i = count($fields)-1;
			while($i > 1) {
				try {
					$this->update(array(
						$fields[$i] => $this->data()->$fields[$i-2],
						$fields[$i-1] => $this->data()->$fields[$i-3]
					), $id);
				}catch(Exception $e) {
					echo '#497 Error changed password '.$e->getMessage();
				}
				$i -= 2;
			}
			try {
				$this->update(array(
					$fields[0] => $password,
					$fields[1] => $date_password
				) ,$id);
			}catch(Exception $ee) {
				echo '#498 Error changed password '.$e->getMessage();
			}
		}
	}//end function passwordHistory

	public function hasPermission($key) {
		$group = $this->_db->get('groups', array('ID', '=', $this->data()->group));
		if($group->count()) {
			$permission = json_decode($group->results()[0]->permission, true);

			if($permission[$key] == true){
				return true;
			}
		}

		return false;
	}//end function hasPermission

	public function logout() {
		return Session::delete($this->_sessionName);
	}//end function logout
	
	public function data() {
		return $this->_data;
	}//end function data

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}//end function isLoggedIn
	
}//end class User