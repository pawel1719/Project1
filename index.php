<HTML lang="pl">
<HEAD>

	<?php include_once 'inc.head.html'; ?>
	
</HEAD>
<BODY>

	<form method="POST" class="login">
	
<?php
//include_once 'inc.menu.html';

require_once 'classes\Input.php';
require_once 'classes\Session.php';
require_once 'classes\Validate.php';
require_once 'classes\User.php';
require_once 'classes\Token.php';
require_once 'classes\DB.php';
require_once 'classes\config.php';

	$user = new User();
	//case when user is logged
	if($user->isLoggedIn()) {
		header('Location: home.php');
	}

	if(Input::exists()) {
		if(Token::check(Input::get('token'))) {
			
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
						Session::flash('welcome', 'Logowanie zakończono pomyślnie');
						header('Location: home.php');
					}
				} else {
					//counter faild login
					$user->counterLogin('counter_failed_logged', 'last_failed_logged',Input::get('username'));
					//error log in
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
				}
				
				echo '</div>';
			}
		}
	}

	if(Session::exist('user_information')) {
		echo '<div class="login__message login__message--info">'. Session::flash('user_information') .'</div>';
	}
	
?>

	
	
		<input placeholder="Username" type="text" class="login__input" name="username" />
		
		<input placeholder="Password" type="password" class="login__input" name="password" />	
		
		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
		
		<div class="login__container">
		
			<button class="login__button login__button--transparentBackground">
				<a href="register.php" class="login__link login__link--decoration">Rejestracja</a>
			</button>

			<input type="submit" value="Zaloguj!" class="login__button" />
			
		</div>
		
	</form>
	



</BODY>
</HTML>