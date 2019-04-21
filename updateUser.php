<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_LOGS;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_TOKEN;
	require_once PATH_TO_CLASSES_DB;

	//save information about visit to file
	Logs::logsToFile('Visited on page');

	$user = new User();
	//checking that user is logged
	if(!$user->isLoggedIn()) {
		Session::flash('user_information', '<p>Musisz się <a href="index.php">zalogować</a> lub <a href="register.php">zarejestrować</a></p>');
		Logs::log('unknow', 'Unauthorized access to app', 'Alert security');
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
				'name' => array(
					'required' => true,
					'min' => 2,
					'max' => 50,
					'isLetters' => true
				),
				'surname' => array(
					'required' => true,
					'min' => 3,
					'max' => 40,
					'isLetters' => true
				),
				'email' => array(
					'required' => true,
					'min' => 3,
					'max' => 40,
					'unique' => 'users'
				),
				'number_phone' => array(
					'required' => true,
					'min' => 7,
					'max' => 40,
					'unique' => 'users'
				),
				'date_birdth' => array(
					'required' => true,
					'date' => true
				),
				'city' => array(
					'required' => true,
					'min' => 3,
					'max' => 40,
					'isLetters' => true
				),
				'street' => array(
					'required' => true,
					'min' => 3,
					'max' => 40
				),
				'no_house' => array(
					'required' => true,
					'min' => 1,
					'max' => 40
				),
				'no_flat' => array(
					'max' => 10
				),
				'consent_rodo' => array(
					'required' => true
				)
			));
		
			if($validation->passed()) {
				$salt = Hash::salt(32);
				
				try {
					
					$user->create(array(
						'name' 			=> Input::get('name'),
						'surname' 		=> Input::get('surname'),
						'email' 		=> Input::get('email'),
						'number_phone' 	=> Input::get('number_phone'),
						'date_birdth' 	=> Input::get('date_birdth'),
						'city' 			=> Input::get('city'),
						'street' 		=> Input::get('street'),
						'no_house' 		=> Input::get('no_house'),
						'no_flat' 		=> (strlen(Input::get('no_house')) > 0) ? Input::get('no_house') : NULL,
						'joined' 		=> date('Y-m-d H:i:s'),
						'group' 		=> 1,
						'consent_rodo' 	=> Input::get('consent_rodo')
					));

					Logs::log($user->data()->username, 'User data changed', 'Information');
					
					Input::destroy('username');
					Input::destroy('password');
					Input::destroy('password_again');
					Input::destroy('name');
					Input::destroy('surname');
					Input::destroy('email');
					Input::destroy('number_phone');
					Input::destroy('date_birdth');
					Input::destroy('city');
					Input::destroy('street');
					Input::destroy('no_house');
					Input::destroy('no_flat');
					Input::destroy('consent_rodo');
					
					Session::flash('user_information', 'You have been registered and can now you log in!');
					header('Location: index.php');
				
				} catch(Exception $e) {
					die($e->getMessage());
				}
			} else {
				Logs::log($user->data()->username, 'Unpassed validation for update data user', 'Error');

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

	<form method="POST" class="login">

		<label for="name" class="login__label">Imię:</label> 
		<input type="text" name="name" value="<?php echo Sanitize::escape($user->data()->name); ?>" class="login__input" />

		<label for="surname" class="login__label">Nazwisko:</label> 
		<input type="text" name="surname" value="<?php echo Sanitize::escape($user->data()->surname); ?>" class="login__input" />

		<label for="email" class="login__label">E-mail:</label>
		<input type="email" name="email" value="<?php echo Sanitize::escape($user->data()->email); ?>" class="login__input" />

		<label for="number_phone" class="login__label">Numer telefonu:</label>
		<input type="text" name="number_phone" value="<?php echo Sanitize::escape($user->data()->number_phone); ?>" class="login__input" />

		<label for="date_birdth" class="login__label">Data urodzenia:</label>
		<input type="date" name="date_birdth" value="<?php echo date('Y-m-d'); ?>" class="login__input" />

		<label for="city" class="login__label">Miasto:</label>
		<input type="text" name="city" value="<?php echo Sanitize::escape($user->data()->city); ?>" class="login__input" />

		<label for="street" class="login__label">Ulica:</label>
		<input type="text" name="street" value="<?php echo Sanitize::escape($user->data()->street); ?>" class="login__input" />

		<label for="no_house" class="login__label">Numer domu:</label>
		<input type="text" name="no_house" value="<?php echo Sanitize::escape($user->data()->no_house); ?>" class="login__input" />

		<label for="no_flat" class="login__label">Numer mieszkania:</label>
		<input type="text" name="no_flat" value="<?php echo Sanitize::escape($user->data()->no_flat); ?>" class="login__input" />

		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
		<input type="submit" value="Zapisz" class="login__button" />
		
	</form>

</BODY>
</HTML>