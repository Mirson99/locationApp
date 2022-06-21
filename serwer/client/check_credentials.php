<?php
	require_once('../model/user.php');

	if (check_user_credentials($_GET['mail'], $_GET['password'])) {
		echo '{"result":1, "errorText":"ok"}';
	} else {
		echo '{"result":0, "errorText":"Wprowadz poprawne dane logowania!"}';
	}

?>