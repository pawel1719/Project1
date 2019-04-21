<?php
	require_once '../../classes/config.php';
	require_once PATH_TO_UP_UP_CLASSES_LOGS;
	require_once PATH_TO_UP_UP_CLASSES_SESSION;
	require_once PATH_TO_UP_UP_CLASSES_USER;

	//save information about visit to file
	Logs::logsToFile('Visited on page');
	
	$user = new User();
	$user->logout();

	Logs::log($user->data()->username, 'User logged out of the application', 'Information');

	Session::flash('user_information', 'Wylogowałeś się!');
	header('Location: ../../index.php');