<?php
	require_once 'classes\User.php';
	require_once 'classes\Session.php';

	$user = new User();
	$user->logout();

	Session::flash('user_information', 'Wylogowałeś się!');
	header('Location: index.php');