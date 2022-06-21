<?php
	//--------------------------------------
	// obsługa sesji
	session_start();
	require_once('../model/user.php');
	require_once('../model/device.php');

	if (!isset($_GET['email']) || !isset($_GET['password'])) {
		die('{"result":0, "deviceId":0, "devicePassword":"Brak danych logowania!"}');
	}


	if (log_in($_GET['email'], $_GET['password'])) {
		$res = add_device ($_GET['email'], $_GET['device_name']);
		if ($res[0] === true) {
			echo ('{"result":1, "deviceId":'.$res[1].', "devicePassword":"'.$res[2].'"}');
			log_out();
		} else {
			echo ('{"result":0, "deviceId":0, "devicePassword":"'.$res[1].'"}');
		}
		
	} else {
		die('{"result":0, "deviceId":0, "devicePassword":"Dane logowania nie prawidłowe!"}');
	}

?>