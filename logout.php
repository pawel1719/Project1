<?php
	require_once 'classes\config.php';
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_SESSION;

	$user = new User();
	$user->logout();

	Session::flash('user_information', 'Wylogowałeś się!');
	header('Location: index.php');