<?php
	require_once('model/db.php');
	require_once('model/user.php');
	$page_help = 'Zaloguj się aby uzyskać dostęp do aplikacji';

	// Przesłano dane logowania
	if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_GET['a'] == 'log_in')){

		$res = log_in($_POST['email'], $_POST['password']);

		if ($res !== true) {
			
			$page_title = "Logowanie";
			require('view/layout/header.php');
			require('view/layout/top_menu.php');

			$login_mail = $_POST['email'];
			$login_error = 'Wprowadzono nieprawidłowy adres Email lub hasło!';

			require('view/login_form.php');

			require('view/layout/footer.php');
		}
	} else {
	// Wyświetl formularz logowania
		
		$page_title = "Logowanie";
		require('view/layout/header.php');
		require('view/layout/top_menu.php');		

		$login_error = '';
		require('view/login_form.php');
		require('view/layout/footer.php');
	}


?>
