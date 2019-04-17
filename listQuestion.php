<?php 
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_DB;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_SESSION;

	//checking that user is logged
	$user = new User();
	if(!$user->isLoggedIn()) {
		Session::flash('user_information', 'I have to log in');
		header('Location: index.php');
	}
?>
<HTML lang="pl-PL">
<HEAD>

	<?php include_once PATH_TO_HEAD; ?>

</HEAD>
<BODY>

<?php

	include_once PATH_TO_MENU;

	$db = DBB::getInstance()->query('SELECT * FROM `question` GROUP BY Question ASC');
	
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