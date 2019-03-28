<?php 
	require_once 'classes/Validate.php';
	require_once 'classes/Input.php';
	require_once 'classes/DB.php';
	require_once 'classes/User.php';
	require_once 'classes/Session.php';

	//checking that user is logged
	$user = new User();
	if(!$user->isLoggedIn()) {
		Session::flash('user_information', 'I have to log in');
		header('Location: index.php');
	}
?>
<HTML lang="pl-PL">
<HEAD>
	<?php include_once 'inc.head.html'; ?>
</HEAD>
<BODY>

<?php
	include 'inc.menu.html';

	$db = DB::getInstance()->query('SELECT * FROM `question` GROUP BY Question ASC');
	
	if(!$db->error()) {
		$results = $db->results();
		$counter = 1;
		
		echo '<table border="1">';
		
		foreach($results as $result) {
			echo '	<tr>
						<td>'. $counter .'</td>
						<td>'. $result->Question .'</td>
						<td><a href="editQuestion.php?id='. $_GET['id']=$result->ID .'">Edytuj</a></td>
						<td><a href="deleteQuestion.php?id='. $_GET['id']=$result->ID .'">Usuń</a></td>
					</tr>';
			$counter++;
		}
		//echo '<script type="text/javascript">confirm("Niepoprawny login lub hasło!");</script>';
		
		echo '</table>';
	}
	
?>

	
</BODY>
</HTML>