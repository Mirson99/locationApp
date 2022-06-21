<?php
	//--------------------------------------
	// obsługa sesji
	session_start();
	
	require_once('../model/map.php');
	require_once('../model/device.php');
	
	$on_mobile_device = isset($_GET['mobile']);
	
	if (!$on_mobile_device) {
		// check is loggd in browser
		if (!is_logged_in()){
			die("Nie zalogowano !");
		}

		$points = get_points($_SESSION['user_mail'], $_GET['id'], $_GET['start'], $_GET['end']);
	}
	 else {
		require_once('../model/db.php');

		// check is loggd on mobile device
		if (!check_device_credentials($_GET['id'], $_GET['device_password'])){
			die("Nie zalogowano !");
		}
		
		$points = get_points_mobile_client($_GET['id'], $_GET['start'], $_GET['end']);
	}
	

	echo points_to_geoJSON($points);

?>