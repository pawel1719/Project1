<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_USER;

	$user = new User();
	if(!$user->isLoggedIn()) {
		header('Location ../../index.php');
	}

	$HTML_user =  Sanitize::escape($user->data()->username); 
?>
<ol>
	<li>
			<a href="home.php">Home</a>
	</li>
	<li><a href="showTickets.php?row=10&page=1">Zgłoszenia</a>
		<ul>
			<li><a href="addTicket.php">Dodaj zgłoszenie</a></li>
			<li></li>
			<li></li>
		</ul>
	</li>
	<li><a href="#">QUIZ</a>
	<ul>
		<li><a href="editQuestion.php">Edytuj pytanie</a></li>
		<li><a href="deleteQuestion.php">Usuń pytanie</a></li>
		<li><a href="listQuestion.php">Lista pytań</a></li>
		<li><a href="randomQuestion.php">Random Question</a></li>
	</ul>
</li>
<li>
	<ul>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</li>
<li><a href="changePassword.php">User <?php echo $HTML_user; ?></a>
		<ul>
			<li><a href="logout.php">Wyloguj się</a></li>
			<li><a href="updateUser.php">Edytuj profil</a></li>
			<li><a href="changePassword.php">Zmień hasło</a></li>
		</ul>
	</li>
</ol>