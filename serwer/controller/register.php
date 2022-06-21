<?php
	require_once('model/db.php');
	require_once('model/user.php');
	$page_help = 'Zarejestruj konto aby móc korzystać z aplikacji:<br>
	Wszystkie pola są wymagane<br><br>
	Email - poprawny adres Email<br><br>
	Hasło:<br>
	musi składać się co najmniej z 5 znaków<br>
	musi zawierać co najmniej jedną cyfrę<br>
	musi zawierać co najmniej jedną dużą literę<br>
	';
	$page_title = "Rejestracja konta";
	
	// Przesłano formularz rejestracji
	if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_GET['a'] == 'register')){

		if (($_POST['password'] != $_POST['password_confirm']) || empty($_POST['password_confirm'])) {
			$password_confirm_error = "Wprowadzone hasła nie zgadzają się!!!";
			$res = array(false, "");
		} else {
			$res = register_user($_POST['email'], $_POST['password']);
		}
		
		// Nie udało się zarejestrować z podanymi danymi
		if ($res[0] === false) {
			$page_title = "Rejestracja konta urzytkownika";
			require('view/layout/header.php');
			require('view/layout/top_menu.php');

			$mail_error = $res[1];
			$password_error = $res[2];
			$register_mail = $_POST['email'];
			require('view/register_form.php');
			
			require('view/layout/footer.php');
		} else {
		// Zarejestrowano - zaloguj automatycznie	
			log_in($_POST['email'], $_POST['password']);
		}
	} else {
	// Wyświetl formularz rejestracji
		$page_title = "Rejestracja konta urzytkownika";
		require('view/layout/header.php');
		require('view/layout/top_menu.php');

		$mail_error = $password_error = $password_confirm_error = '';
		require('view/register_form.php');
		
		require('view/layout/footer.php');
	}
?>
