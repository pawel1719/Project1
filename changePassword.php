<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_TOKEN;
	require_once PATH_TO_CLASSES_DB;
?>
<HTML lang="pl-PL">
<HEAD>

	<?php require_once PATH_TO_HEAD; ?>

</HEAD>
<BODY>
<?php
	require_once PATH_TO_MENU;

	$user = new User();
	//checking that user is logged
	if(!$user->isLoggedIn()) {
		header('Location: index.php');
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'password_old' => array(
					'required' => true
				),
				'password_new' => array(
					'required' => true,
					'min' => 4,
					'max' => 50,
					'strongPassword' => true
				),
				'password_new_again' => array(
					'required' => true,
					'min' => 4,
					'max' => 50,
					'matches' => 'password_new'
				)
			));
			
			if($validation->passed()) {
				if(Hash::make(Input::get('password_old'), $user->data()->salt) !== $user->data()->password) {
					echo 'Stare hasło jest niepoprawne!';
				} else {
					$salt = Hash::salt(42);
					$user->update(array(
						'password' => Hash::make(Input::get('password_new'), $salt),
						'salt' => $salt,
						'password_date' => date('Y-m-d H:i:s')
					));
					$fields = ['password_old_1', 
							  'date_changed_password_old_1', 
							  'password_old_2', 
							  'date_changed_password_old_2', 
							  'password_old_3', 
							  'date_changed_password_old_3'
					];
					$user->passwordHistory($fields, Hash::make(Input::get('password_old')), $user->data()->password_date, $user->data()->ID);

					Session::flash('password', 'Hasło zostało zmienione!');
				}
			} else {
				//show the error for validation 
				foreach($validation->errors() as $error) {
					echo $error . '<br />';
				}
				echo '--------------------------------------------';
			}
		}
	}

	if(Session::exist('password')) {
		echo Session::flash('password');
	}

?>

<form method="POST">
	Stare hasło: <input type="password" placeholder="Stare hasło" name="password_old"/> <br/>

	Nowe hasło: <input type="password" placeholder="Nowe hasło" name="password_new"/> <br/>

	Potwierdź hasło: <input type="password" placeholder="Potwierdź hasło" name="password_new_again"/> <br/
	
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" value="Zmień">
	
</form>

</BODY>
</HTML>