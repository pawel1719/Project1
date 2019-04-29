<?php 
require_once 'config.php';
require_once PATH_TO_DB;
require_once PATH_TO_SESSION;

class Ticket {
	private $_db,
			$_data;

	public function __construct($user = null) {
		$this->_db = DBB::getInstance();
	}

	public function create($fields = array()) {
		//adding data to database
		if(!$this->_db->insert('ticket', $fields)) {
			throw new Exception('#80234 There was a problem adding a ticket');
		}
	}

	public function addComment($fields = array()) {
		//adding coment to ticket
		if(!$this->_db->insert('comments', $fields)) {
			throw new Exception('#80236 There was a  problem adding a comment');
		}else{ 
			return true; 
		}
	}// end

	public function getCommentsForTicket($id) {
		$query = 'SELECT c.ID
						,c.id_user
						,CONCAT(u.name, " ", u.surname) user_name
						,c.id_ticket
						,c.visibility
						,c.comment
						,c.date_add 
				FROM `comments` c LEFT JOIN users u ON c.id_user=u.ID
				WHERE c.id_ticket = '. $id .'
				ORDER BY c.date_add ASC';
		
		return $this->_db->query($query)->results();
	}

	public function showData($query) {
		return $this->_db->query($query)->results();
	}//end

	public function showDataTickets($syntax = 'id=1') {
		$this->_data = $this->_db->query('
					SELECT 	 t.`ID` id
							,t.`subject` subject_ticket
							,t.`description` desc_ticket
							,t.`id_declarant` id_declarant_ticket
							,CONCAT(u.name, " ", u.surname) declarant
							,u.group group_user
							,t.`id_operator_ticket` id_operator
							,CONCAT(u2.name, " ", u2.surname) operator
							,t.`id_ticketStatus` id_status
							,s.name_status status
							,t.`id_ticketQueue` id_queue
							,q.queue queue
							,t.`id_ticketPriority` id_priority
							,p.name_priority priority
							,t.`date_create_ticket` date_create
							,t.`date_acceptance_ticket` date_acceptance
							,t.`date_planned_ending` date_planned_end
							,t.`date_ended_ticket` date_ended
							,t.`id_linked_ticket` id_linked
							,t.`id_linker` id_linker_person
							,t.`date_linked` date_linked_ticket
					FROM ticket t LEFT JOIN users u ON t.id_declarant=u.ID 
								  LEFT JOIN users u2 ON t.id_operator_ticket=u2.ID
								  LEFT JOIN ticketStatus s ON t.id_ticketStatus=s.ID
								  LEFT JOIN ticketQueue q ON t.id_ticketQueue=q.ID
								  LEFT JOIN ticketPriority p ON t.id_ticketPriority=p.ID
					'.$syntax
			)->results();
		
		return $this->_data;
	}

	public function numberPages($link, $actual_page, $number_results = 10, $show_number = 5) {
		//checking type of value, if isnt numeric set a default value of fucntion
		$actual_page 	= (is_numeric($actual_page)) 	? $actual_page 		: 1;
		$number_results = (is_numeric($number_results)) ? $number_results 	: 10;
		$show_number 	= (is_numeric($show_number)) 	? $show_number 		: 5;
		
		//number of rows from db
		$this->_data = $this->_db->query('SELECT MAX(ID) row FROM ticket ')->results();
		$max = (!empty($this->_data[0]->row)) ? $this->_data[0]->row : 0;
		$max = ($max%$number_results !== 0) ? ($max+$number_results) : $max;
		$numbers = '';

		// numbers from range
		$no = $max/$number_results;								//middle numberce
		$top_value = $actual_page+((int)($show_number/2));		//top number
		$bottom_value = $actual_page-((int)($show_number/2));	//bottom number


		if($bottom_value >= 1 && $top_value <= $no) {
			//case when numbers are on the range
			$max = ($actual_page+((int)($show_number/2)))*$number_results;
		}else if($top_value > $no){
			//case when numbers are behind a top range
			$max = $no*$number_results;
		}else {
			//case when numbers are behind a bottom range
			if($show_number<$no){
				$max = $show_number*$number_results;
			}else{
				$max = $no*$number_results;
			}
		}

		for($i=0; $i<$show_number; $i++) {
			$tmp = (int)($max/$number_results);
			if($tmp < 1) { 
				break; 
			}

			//links to the pages
			$numbers = '<a href="'. $link . $tmp .'"><button class="btn btn-secondary btn-link">'. $tmp .'</button></a>' . $numbers;
			$max -= $number_results; //decrements variable
		}
		
		return $numbers;
	}

	public function jsonToArray($json) {
		return json_decode($json, true);
	}

	public function arrayToJson($array = array()) {
		return json_encode($array);
	}

}

?> 