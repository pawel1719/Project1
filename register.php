<?php 
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_DB;
	require_once PATH_TO_CLASSES_TOKEN;
	require_once PATH_TO_CLASSES_SANITIZE;
	require_once PATH_TO_CLASSES_HASH;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_SENDMAIL;
?>

<HTML lang="pl-PL">
<HEAD>

	<?php include_once PATH_TO_HEAD; ?>

</HEAD>
<BODY>

<?php

	$user = new User();
	//redirect if is logged
	if($user->isLoggedIn()) {
		header('Location: home.php');
	}
	
	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {

			//validate for inputs register
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
				//generate hash to password
				$salt = Hash::salt(80);
				

				try {
					//adding user information to database
					$user->create(array(
						'username' => Input::get('username'),
						'password' => Hash::make(Input::get('password'), $salt),
						'salt' => Sanitize::escape($salt),
						'password_date' => @date('Y-m-d H:i:s'),
						'name' => Input::get('name'),
						'surname' => Input::get('surname'),
						'email' => Input::get('email'),
						'number_phone' => Input::get('number_phone'),
						'date_birdth' => Input::get('date_birdth'),
						'city' => Input::get('city'),
						'street' => Input::get('street'),
						'no_house' => Input::get('no_house'),
						'no_flat' => (strlen(Input::get('no_house')) > 0) ? Input::get('no_house') : NULL,
						'joined' => @date('Y-m-d H:i:s'),
						'group' => 1,
						'consent_rodo' => Input::get('consent_rodo')
					));

					//message to the user who successfully finish register
					$HTML = '
					<HTML><HEAD></HEAD>
					<BODY>
						<p><h2>Witaj '. Input::get('name') .'!</h2></p>
						<p>Zapraszyam do zapoznania się z naszym portalem. Mamy dużo nowości i cały czas się orzwijamy. Zapraszyam do zapoznania się z naszym portalem. Mamy dużo nowości i cały czas się orzwijamy.Zapraszyam do zapoznania się z naszym portalem. Mamy dużo nowości i cały czas się orzwijamy.</p> <p>
						<h3>Założyciele portalu</h3>
						<h5>Mail: mail.test.app@wp.pl</h5>
						</p>
					</BODY>
					</HTML>
					';

					//send message for a new user
					$send = new SendMail(true);
					$send->createMessage( Input::get('email'), Input::get('Name') .' '. Input::get('surname'), 'Witaj w naszym portalu!', $HTML);
					
					//clean the variables
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
					
					//sending information about successful login and redirect to the login page
					Session::flash('user_information', 'You have been registered and can now you log in!');
					header('Location: index.php');
				
				} catch(Exception $e) {
					die($e->getMessage());
				}

			} else {
				//error from validation
				echo '<div class="login__message login__message--alert">';
				
				foreach($validation->errors() as $error => $details) {
					echo $details . '<br />';
				}//end loop
				echo '</div>';
			}
		}
	}

?>

	<!-- beginning of registration form -->
	<form method="POST" class="login">

		<button class="login__backButton">            
			<a href="index.php" class="login__link login__link--decoration">Wróć do logowania</a>		
		</button>				

		<label for="username" class="login__label">Login:</label>
		<input type="text" name="username" value="<?php echo Sanitize::escape(Input::get('username')); ?>" class="login__input" />				

		<label for="password" class="login__label">Hasło:</label>
		<input type="password" name="password" class="login__input"/>

		<label for="password_again" class="login__label">Powtórz hasło:</label>
		<input type="password" name="password_again" class="login__input" />
		
		<label for="name" class="login__label">Imię:</label>
		<input type="text" name="name" value="<?php echo Sanitize::escape(Input::get('name')); ?>" class="login__input" />
		
		<label for="surname" class="login__label">Nazwisko:</label>
		<input type="text" name="surname" value="<?php echo Sanitize::escape(Input::get('surname')); ?>" class="login__input" />
		
		<label for="email" class="login__label">E-mail:</label>
		<input type="email" name="email" value="<?php echo Sanitize::escape(Input::get('email')); ?>" class="login__input" />
		
		<label for="number_phone" class="login__label">Numer telefonu:</label>
		<input type="text" name="number_phone" value="<?php echo Sanitize::escape(Input::get('number_phone')); ?>" class="login__input" />
		
		<label for="date_birdth" class="login__label" >Data urodzenia:</label>
		<input type="date" name="date_birdth" value="<?php echo @date('Y-m-d'); ?>" class="login__input" />
		
		<label for="city" class="login__label">Miasto:</label>
		<input type="text" name="city" value="<?php echo Sanitize::escape(Input::get('city')); ?>" class="login__input" />
		
		<label for="street" class="login__label">Ulica:</label>
		<input type="text" name="street" value="<?php echo Sanitize::escape(Input::get('street')); ?>" class="login__input" />
		
		<label for="no_house" class="login__label">Numer domu:</label>
		<input type="text" name="no_house" value="<?php echo Sanitize::escape(Input::get('no_house')); ?>" class="login__input" />
		
		<label for="no_flat" class="login__label">Numer mieszkania:</label>
		<input type="text" name="no_flat" value="<?php echo Sanitize::escape(Input::get('no_flat')); ?>" class="login__input" />

		<!-- back button to login page -->
		<div class="login__container login__container--justify">	
			<input type="checkbox" name="consent_rodo" value="1" /> 
			<label for="consent_rodo" class="login__label login__label--margin">Akceptuję <a href="#" class="login__link login__link--color">regulamin</a></label>
		</div>

		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
		<input type="submit" value="Zarejestruj" class="login__button" />

	</form>
	
</BODY>
</HTML>