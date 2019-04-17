<?php
	require_once 'classes\config.php';
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_TOKEN;
	require_once PATH_TO_CLASSES_DB;

	$user = new User();
	//checking that user is logged
	if(!$user->isLoggedIn()) {
		Session::flash('user_information', '<p>Musisz się <a href="index.php">zalogować</a> lub <a href="register.php">zarejestrować</a></p>');
		header('Location: index.php');
	}
?>
<HTML>
<HEAD>

	<?php include_once PATH_TO_HEAD; ?>

</HEAD>
<BODY>
<?php
	include_once PATH_TO_MENU;

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'email' => array(
					'required' => true,
					'min' => 2,
					'max' => 50,
					'isLetters' => true
				)));
			
			if($validation->passed()) {
				try {
					echo 'Successful validation!<br/>';
					echo '============================';
					/*
					$user->update(array(
						'name' => Input::get('name')
					));
					Session::flash('update', 'Successful update your profil!');
					header('refresh: 3;');
					*/

				} catch(Exception $e) {
					die($e->getMessage());
				}
				
			} else {
				//show the error for validation 
				foreach($validation->errors() as $error) {
					echo $error . '<br />';
				}
			}
		}
	}

	if(Session::exist('update')) {
		echo Session::flash('update');
	}
	
	


?>
<p>
	Testowanie pola formularza
</p>
<form method="POST">
	Email: <input type="text" name="email" value="<?php echo Sanitize::escape(Input::get('email')); ?>"/> 

	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" value="Zapisz">
	
</form>

</BODY>
</HTML>