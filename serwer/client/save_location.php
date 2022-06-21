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

		// urządzenie zalogowane - dodaj nową lokalizację do bazy
		$add_query = "INSERT INTO `location` (`loc_id`, `dev_id`, `loc_time`, `loc_latitude`, `loc_longtitude`) VALUES (NULL, '".$_GET['dev_id']."', CURRENT_TIMESTAMP, '".$_GET['lat']."', '".$_GET['lon']."');";
		$conn->query($add_query);

		// ustaw czas aktualizacji (dla statusu offline / online)
		$update_query = "UPDATE `device` SET `last_seen` = CURRENT_TIMESTAMP WHERE `device`.`dev_id` = ".$_GET['dev_id'].";";
		$conn->query($update_query);
		
		echo 'ok';
	}
?>