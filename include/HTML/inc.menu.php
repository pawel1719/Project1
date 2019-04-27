<?php
	require_once 'classes/config.php';
	require_once PATH_TO_CLASSES_SANITIZE;
	require_once PATH_TO_CLASSES_USER;

	$user = new User();
	if(!$user->isLoggedIn()) {
		header('Location ../../index.php');
	}

	$HTML_user =  Sanitize::escape($user->data()->username); 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="#">APP PS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

      	<li class="nav-item active"><a class="nav-link" href="home.php">Home</a></li>
	  
	  	<li class="nav-item active">
			<a class="nav-link dropdown-toggle" href="showTickets.php?data=tickets&row=10&page=1" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Zgłoszenia
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="showTickets.php?data=new_tickets&row=10&page=1">Nowe zgłoszenia</a>
				<a class="dropdown-item" href="showTickets.php?data=tickets&row=10&page=1">Wszystkie zgłoszenia</a>
				<a class="dropdown-item" href="addTicket.php">Dodaj zgłoszenie</a>
			</div>
		</li>

     	 <li class="nav-item active">
        	<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         		QUIZ
        	</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="editQuestion.php">Edytuj pytanie</a>
				<a class="dropdown-item" href="deleteQuestion.php">Usuń pytanie</a>
				<a class="dropdown-item" href="listQuestion.php">Lista pytań</a>
				<a class="dropdown-item" href="randomQuestion.php">Random Question</a>
			</div>
		</li>

		<li class="nav-item active">
        	<a class="nav-link dropdown-toggle" href="changePassword.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				User <?php echo $HTML_user; ?>
        	</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="updateUser.php">Edytuj profil</a>
				<a class="dropdown-item" href="changePassword.php">Zmień hasło</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="include/PHP/logout.php">Wyloguj się</a>
			</div>
		</li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>
