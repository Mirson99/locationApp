<?php
	//--------------------------------------
	// obsługa sesji
	session_start();
	require_once('../model/user.php');
	require_once('../model/device.php');

// aded test device (res, dev_id, dev_password): Array ( [0] => 1 [1] => 47 [2] => AFa7kSfXl29WeqKNsxP8 )

	if (!isset($_GET['dev_id']) || !isset($_GET['dev_pass'])) {
		die('Error: Nie podano danych logowania urzadzenia!');
	} else if (check_device_credentials($_GET['dev_id'], $_GET['dev_pass']) == false) {
		die('Error: Nieprawidlowe dane logowania urzadzenia!');
	} else {

		// urządzenie zalogowane - wyświetl mapkę z historią lokalizacji:
		$device_id = $_GET['dev_id'];
		$device_name = get_device_name($device_id);
		$device_password = $_GET['dev_pass'];
		$page_title = "Historia lokalizacji urządzenia (".$device_name.")";

		require_once('../view/layout/header_in_device.php');
		// hidden data for JS script...
		echo "				\n<!-- for js loader-->\n".'<input id="device_id" value="'.$device_id.'" type="hidden">'."\n";
		echo "				\n<!-- for js loader-->\n".'<input id="device_password" value="'.$device_password.'" type="hidden">'."\n";
		


		require('../view/map_in_device.php');

		require('../view/layout/footer_in_device.php');

	}
?>