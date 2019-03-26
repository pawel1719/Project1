<?php

class Sanitize {
	
	public static function escape($string) {
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}//end escape
	
}//end class Sanitize