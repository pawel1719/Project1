<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_INPUT;
	require_once PATH_TO_CLASSES_LOGS;
	require_once PATH_TO_CLASSES_SESSION;
	require_once PATH_TO_CLASSES_VALIDATE;
	require_once PATH_TO_CLASSES_USER;
	require_once PATH_TO_CLASSES_TOKEN;
	require_once PATH_TO_CLASSES_DB;
?>
<HTML lang="pl=PL">
<HEAD>

	<?php include_once PATH_TO_HEAD; ?>
	
</HEAD>
<BODY>

	<form method="POST" class="login">
		
<?php

	$user = new User();
	//case when user is logged
	if($user->isLoggedIn()) {
		header('Location: home.php');
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
			//valiate for inputs
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array('required' => true),
				'password' => array('required' => true)
			));
			
			if($validation->passed()) {
				$login = $user->login(Input::get('username'),Input::get('password'));
				
				if($login) {
					//counter success login
					if($user->counterLogin('counter_success_logged', 'last_success_logged',Input::get('username'))) {
						
						//login is successful 
						Logs::log(Input::get('username'), 'Succesfull logged!', 'Success');

						Session::flash('welcome', 'Logowanie zakończono pomyślnie');
						header('Location: home.php');
					}
				} else {
					//counter faild login
					$user->counterLogin('counter_failed_logged', 'last_failed_logged',Input::get('username'));
					Logs::log(Input::get('username'), 'Incorrect login or password', 'Error');

					//error login
					Session::flash('faild_login', 'Niepoprawny login lub hasło!');
					if(Session::exist('faild_login')) {
						echo '<div class="login__message login__message--alert">'. Session::flash('faild_login') .'</div>';
					}
				}
			} else {
				//show the error for validation
				echo '<div class="login__message login__message--alert">';
				
				foreach($validation->errors() as $error) {
					echo $error . '<br />';
				}//end loop			
				echo '</div>';

				//logs error
				Logs::log(Input::get('username'), 'Unpassed validation for login', 'Error');
			}
		}
	}
	//information from user
	if(Session::exist('user_information')) {
		echo '<div class="login__message login__message--info">'. Session::flash('user_information') .'</div>';
	}
	
?>

	
		<!-- input to form -->
		<input placeholder="Username" type="text" class="login__input" name="username" />
		
		<input placeholder="Password" type="password" class="login__input" name="password" />	
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
		
		<div class="login__container">

			<!-- link to register -->
			<button class="login__button login__button--transparentBackground">
				<a href="register.php" class="login__link login__link--decoration">Rejestracja</a>
			</button>

			<input type="submit" value="Zaloguj!" class="login__button" />
			
		</div>
		
	</form>
	



</BODY>
</HTML>