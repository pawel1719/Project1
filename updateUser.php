<HTML>
<HEAD>
	<?php include_once PATH_TO_HEAD; ?>
</HEAD>
<BODY>
<?php
include_once PATH_TO_MENU;

require_once 'classes\config.php';
require_once PATH_TO_CLASSES_INPUT;
require_once PATH_TO_CLASSES_SESSION;
require_once PATH_TO_CLASSES_VALIADTE;
require_once PATH_TO_CLASSES_USER;
require_once PATH_TO_CLASSES_TOKEN;
require_once PATH_TO_CLASSES_DB;

	$user = new User();
	//checking that user is logged
	if(!$user->isLoggedIn()) {
		Session::flash('user_information', '<p>Musisz się <a href="index.php">zalogować</a> lub <a href="register.php">zarejestrować</a></p>');
		header('Location: index.php');
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'required' => true,
					'min' => 3,
					'max' => 40,
					'unique' => 'users'
				),
				'password' => array(
					'required' => true,
					'min' => 4,
					'max' => 30,
					'strongPassword' => true
				),
				'password_again' => array(
					'required' => true,
					'matches' => 'password'
				),
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
						'username' 		=> Input::get('username'),
						'password' 		=> Hash::make(Input::get('password'), $salt),
						'salt' 			=> Sanitize::escape($salt),
						'password_date' => date('Y-m-d H:i:s'),
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

	<form method="POST">
		Login: <br/>
		<input type="text" name="username" value="<?php echo Sanitize::escape($user->data()->username); ?>" /><br/>
		Imię: <br/>
		<input type="text" name="name" value="<?php echo Sanitize::escape($user->data()->name); ?>" /><br/>
		Nazwisko: <br/>
		<input type="text" name="surname" value="<?php echo Sanitize::escape($user->data()->surname); ?>" /><br/>
		E-mail: <br/>
		<input type="email" name="email" value="<?php echo Sanitize::escape($user->data()->email); ?>" /><br/>
		Numer telefonu: <br/>
		<input type="text" name="number_phone" value="<?php echo Sanitize::escape($user->data()->number_phone); ?>" /><br/>
		Data urodzenia: <br/>
		<input type="date" name="date_birdth" value="<?php echo @date('Y-m-d'); ?>" /><br/>
		Miasto: <br/>
		<input type="text" name="city" value="<?php echo Sanitize::escape($user->data()->city); ?>" /><br/>
		Ulica: <br/>
		<input type="text" name="street" value="<?php echo Sanitize::escape($user->data()->street); ?>" /><br/>
		Numer domu: <br/>
		<input type="text" name="no_house" value="<?php echo Sanitize::escape($user->data()->no_house); ?>" /><br/>
		Numer mieszkania: <br/>
		<input type="text" name="no_flat" value="<?php echo Sanitize::escape($user->data()->no_flat); ?>" /><br/><br/>
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
		<input type="submit" value="Zapisz" />
	</form>

</BODY>
</HTML>