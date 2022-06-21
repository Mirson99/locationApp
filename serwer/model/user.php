<?php
require_once('db.php');
require_once('libs/password.php');


//-------------------------------------------
function check_mail($mail) {
	if (empty($mail))
		return false;
	
	return filter_var($mail, FILTER_VALIDATE_EMAIL);
}

//-------------------------------------------
function check_password_string($pass) {
	if (empty($pass))
		return array(false, "Hasło nie może być puste");
	
	if (strlen($pass) < 5)
		return array(false, "Hasło jest za krótkie (minimun 5 znaków)");
	
	if (!preg_match("/^[a-zA-Z0-9!@#_\-]*$/", $pass))
		return array(false, "Hasło może zawierać duże i małe litery, liczby oraz znaki specjalne:!@#_");
	
	if (!preg_match("/[A-Z]/", $pass))
		return array(false, "Hasło musi zawierać co najmniej jedną dużą literę!");
	
	if (!preg_match("/[0-9]/", $pass))
		return array(false, "Hasło musi zawierać co najmniej jedną cyfrę!");
	
	return array(true, "");
}

//-------------------------------------------
function register_user($mail, $password, $user_name = "John Doe") {
	global $conn;
	
	$mail_error = $pass_error = false;
	if (!check_mail($mail))
		$mail_error = "Podaj prawidłowy adres Email";
	
	$pass_res = check_password_string($password);
	if ($pass_res[0] == false) {
		$pass_error = $pass_res[1];
	}

	if ($mail_error || $pass_error) {
		return array(false, $mail_error, $pass_error);
	}
	
	
	$result = $conn->query("SELECT `usr_mail` FROM `user` WHERE `usr_mail` = '".$mail."' LIMIT 1");
	if ($result->num_rows > 0)
		return array(false, "Użytkownik o podanym adresie już istnieje w bazie!", "");
	
	
	$pass_hash = password_hash($password, PASSWORD_DEFAULT);
	
	$add_user_query = "INSERT INTO `user` (`usr_id` ,`usr_mail` ,`usr_pass` ,`usr_name`) VALUES (NULL , '".$mail."', '".$pass_hash."', '".$user_name."');";
	$result = $conn->query($add_user_query);
	
	return array($result);
}


//-------------------------------------------
function is_logged_in() {
	return ($_SESSION['loggd'] == md5($_SESSION['loggd_time']));
}

//-------------------------------------------
function log_out() {
      $_SESSION = array();
      session_destroy();
}

//-------------------------------------------
function check_user_credentials($mail, $password) {
	global $conn;

	if (empty($mail) || empty($password)) {
		return false;
	}
	
	$mail = addslashes(substr($mail, 0, 255));
	$password = addslashes(substr($password, 0, 255));

	$result = $conn->query("SELECT `usr_pass` FROM `user` WHERE `usr_mail` = '".$mail."' LIMIT 1");
	if ($result->num_rows < 1)
		return false;
	
	
	$usr_data = $result->fetch_object();
	if (password_verify($password, $usr_data->usr_pass)) {
		return true;
	}
	
	return false;
}

//-------------------------------------------
function log_in($mail, $password) {
	global $user_mail;

	$mail = addslashes(substr($mail, 0, 255));
	$password = addslashes(substr($password, 0, 255));

	if(check_user_credentials($mail, $password)) {
		$_SESSION['loggd_time'] = time(); 
		$_SESSION['loggd'] = md5($_SESSION['loggd_time']);

		$_SESSION['user_mail'] = $mail;
		return true;
	} else {
		return false;
	}

}


//-------------------------------------------
function get_user_id($mail) {
	global $conn;

	// get user id...
	$result = $conn->query("SELECT `usr_id` FROM `user` WHERE `usr_mail` = '".$mail."' LIMIT 1");
	if ($result->num_rows < 1)
		return array(false, "Brak ID urzytkownika w bazie o.O (".$usr_email.")");
	
	return $result->fetch_object()->usr_id;	
}
?>