<HTML lang="pl-PL">
<HEAD>
	<?php include_once 'inc.head.html'; ?>
</HEAD>
<BODY>
<?php
include_once 'inc.menu.html';

require_once 'classes\Input.php';
require_once 'classes\Session.php';
require_once 'classes\Validate.php';
require_once 'classes\User.php';
require_once 'classes\Token.php';
require_once 'classes\DB.php';
require_once 'classes\config.php';

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
	Stare hasło: <input type="password" name="password_old"/> <br/>
	Nowe hasło: <input type="password" name="password_new"/> <br/>
	Potwierdź hasło: <input type="password" name="password_new_again"/> <br/>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" value="Zmień">
</form>

</BODY>
</HTML>