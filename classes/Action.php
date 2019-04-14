<?php

class Action {
	
	public static function randomNo($tab = array()) {
		$counter = count($tab);
		if($counter > 0) {
			//random number from 0 to array counter
			$no = mt_rand(0, $counter - 1);
			
			return $no;
		} else {
			return false;
		}
	}//function
	
	public static function deleteNumberFromArray($tab = array(), $to_delete) {
		$result = array();
		for($i = 0, $j = 0; $i < count($tab); $i++, $j++) {
			if($tab[$i] === $to_delete) {
				$i++;
			} 
			$result[$j] = $tab[$i];
		}
		
		return $result;
	}//function
	
	public static function randomFourNumber() {
		$numbers = array();
		$counter = 0;

		while(count($numbers) != 4) {
			$rand = mt_rand(0,3);
			$bool = false;
			foreach($numbers as $l) {
				if($rand === $l) $bool = true;
			}
			//add to array number if not exist
			if($bool === false ) {
				$numbers[$counter] = $rand;
				$counter++;
			}
		}
		
		return $numbers;
	}//function

	public static function timeToNow($date_end) {
		$date_end = new DateTime($date_end);
		$date_now = new DateTime('now');

		$result = '';
		//time difference array
		$diff = $date_now->diff($date_end);

		$result .= ($diff->days > 0) ? ($diff->days .' dni<br/>') : '';
		$result .= ($diff->h > 0) ? ($diff->h .':') : '00:';
		$result .= ($diff->i > 0) ? ($diff->i .':') : '00:';
		$result .= ($diff->s > 0) ? ($diff->s .' godz.')  : '00 godz.';
		$result = ($result == '00:00:00 godz.') ? ('0 godz.')  : $result;

		return $result;
	}//function

	public static function partText($text, $length) {
		if(strlen($text) > $length) {
			$text = substr($text, 0, $length);
			return $text .'..';
		}else{
			return substr($text, 0, $length);
		}
	}//function
	
}//end class Action

