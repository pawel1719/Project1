<?php

class Input {
	public static function exists($type = 'POST') {
		switch($type) {
			case 'POST' :
				return (!empty($_POST)) ? true : false;
			break;
			case 'GET' :
				return (!empty($_GET)) ? true : false;
			break;
			default: 
				return false;
			break;
		}
	}//end function exists
	
	public static function get($item) {
		if(isset($_POST[$item])) {
			return $_POST[$item];
		} else if(isset($_GET[$item])) {
			return $_GET[$item];
		}
		
		return '';
	}//end function get

	public static function set($item, $value, $type = 'GET') {
		switch($type) {
			case 'GET':
				return $_GET[$item] = $value;
			break;		
			case 'POST':
				return $_POST[$item] = $value;
			break;
			default:
				return '404';
			break;
		}
	}//end function set
	
	public static function destroy($name) {
		if(isset($_POST[$name])) {
			unset($_POST[$name]);

			return true;
		} else if(isset($_GET[$name])) {
			unset($_GET[$name]);
			
			return true;
		} else if(isset($_FILES[$name])) {
			unset($_FILES[$name]);

			return true;
		}
		
		return false;
	}//end function unset
	
}//end class Input