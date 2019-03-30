<?php
require_once 'config.php';
require_once PATH_TO_DB;
require_once PATH_TO_SANITIZE;

class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;
			
	public function __construct() {
		$this->_db = DB::getInstance();
	}//end constructor
	
	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {
					
				empty($source[$item]) ? $value = '' : $value = trim($source[$item]);
				//$value = Sanitize::escape($value);
				$item = Sanitize::escape($item);
					
				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required!");
				} else if(!empty($value)) {				
					switch($rule) {
						//checking cases of validations
						case 'min' :
							//checker manimum case on variables
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must be a minium of {$rule_value} characters!");
							}
						break;
						case 'max' :
							//checker maximum case on variables
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must be a maximum of {$rule_value} characters!");
							}
						break;
						case 'matches' :
							//checker matches to diffret form value
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
						break;
						case 'unique' :
							//checker unique valu on database
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$item} already exists!");
							}
						break;
						case 'date' :
							//validaion date
							if($value == @date('Y-m-d')) {
								$this->addError("{$item} can't be a default value");
							}
						break;
						case 'isNumeric' :
							//validaion that cases are numbers
							if(is_numeric($value) !== $rule_value) {
								$this->addError("{$item} must be a numeric type!");
							}
						break;
						case 'isLetters' :
							//validation that case are letters
							if(preg_match("/^([A-Za-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]{2,})$/", $value) != $rule_value) {
								$this->addError("{$item} must be a letters!");
							}
						break;
						case 'regularExpression' :
							if(preg_match($rule_value, $value) !== 1) {
								$this->addError("{$item} contains illegal characters");
							}
						break;
						case 'strongPassword' :
							//validations a strong password upercase and lowercase, number and special charset with out sapce
							if(preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?=.*[A-Z])(?!.*\s).{2,}$/", $value) !== 1) {
								$this->addError("{$item} must be used upper case, lower case, numbers special chars for exaple $,%,#,@");
							}
						break;
					}//end switch	
				}
			}
		}
		
		//if dont have any error
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
		
		return $this;
	}//end function check
	
	private function addError($error) {
		$this->_errors[] = $error;
	}//end function addError
	
	public function errors() {
		return $this->_errors;
	}//end function errors
	
	public function passed() {
		return $this->_passed;
	}//end function passed
	
}//end class