<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_LOGS;
	require_once PATH_TO_CLASSES_SANITIZE;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_USER;

	//save information about visit to file
	Logs::logsToFile('Visited on page');
?>

<HTML lang="pl-PL">
<HEAD>

	<?php include_once PATH_TO_HEAD; ?>

</HEAD>
<BODY>

<?php 
	include_once PATH_TO_MENU;


	if(Session::exist('register') || Session::exist('welcome')) {
		echo Session::flash('register');
		echo Session::flash('welcome');
		echo '<hr/>';
	} else if(Session::exist(SESSION_NAME)) {
		//echo Session::get(SESSION_NAME);
	}

	$user = new User();
	if($user->isLoggedIn()) {
		echo '<p>Hello <a href="changePassword.php">'. Sanitize::escape($user->data()->username) .'</a>!</p>';
		if($user->hasPermission('admin')) {
			echo 'You are administrator!';
		}
		echo '<p><a href="logout.php">Wyloguj się</a></p>';
	} else {
		Session::flash('user_information', '<p>Musisz się <a href="index.php">zalogować</a> lub <a href="register.php">zarejestrować</a></p>');
		header('Location: index.php');
	}
?>


</BODY>
</HTML>