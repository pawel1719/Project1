<?php

class Hash {
	
	public static function make($string, $salt = '') {
		//return hashing string with salt
		return hash('sha256', $string . $salt);
	}
	
	public static function salt($length) {
		return self::randString($length);
	}
	
	public static function unique() {
		return self::make(self::randString(50, 3));
	}

	public static function randString($length, $chose = 0) {
		//charsets arrays to random
		$option = [
			'qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ1234567890+-*[]{}(),.=_',	// 0
			'qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ1234567890',				// 1
			'qwertyuioplkjhgfdsazxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ',							// 2
			'1234567890'																	// 3
		];
		$chars 			= $option[$chose];
		$result 		= '';
		$length_string 	= strlen($chars);

		//random charset from string
		for($i=0; $i<$length; $i++) {
			$no = mt_rand(0, $length_string-1);
			$result .=  $chars[$no];
		}

		return $result;
	}
	
}//end class hash
