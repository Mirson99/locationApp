<?php
	require_once('model/db.php');
	require_once('model/user.php');
	require_once('model/device.php');
	
	$page_help = 'Lista urządzeń użytkownika - kliknik na ikonę obok urządzenia:<br />
	<img src="view/images/map.png" style="height:40px">Aby wyświetić lokalizację<br />
	<img src="view/images/delete.png" style="height:40px">Aby usunąć urządzenie z twojego konta<br />
	<img src="view/images/rename.png" style="height:40px">Aby zmienić nazwę urządzenia<br />
	';
	$page_title = "moje urządzenia";

	require('view/layout/header.php');
	require('view/layout/top_menu_loggd.php');

	if (!is_logged_in()){
		die("Nie zalogowano !");
	}
	
	
	//---------------------
	function show_user_devices() {
		global $conn;
		
		$devices = load_device_list($_SESSION['user_mail']);
		include('view/device_list.php');
	}

	//---------------------
	switch($_GET['a']){
		
		//---------------------
		case 'rename':
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$res = rename_device($_SESSION['user_mail'], $_GET['id'], $_POST['newName']);
				
				if ($res[0] == false) {
					$name_error = $res[1];
					$device_id = $_GET['id'];
					$device_name = get_device_name($device_id);
					include('view/device_rename.php');
				
				} else {
					show_user_devices();
				}

			} else {
				$device_id = $_GET['id'];
				$device_name = get_device_name($device_id);
				include('view/device_rename.php');
			}
		break;
		
		//---------------------
		case 'delete':
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				delete_device($_GET['id'], $_SESSION['user_mail']);
				
				show_user_devices();
			} else {
				$device_id = $_GET['id'];
				$device_name = get_device_name($device_id);
				include('view/device_delete.php');
			}
		break;
		
		//---------------------
		default:
			show_user_devices();
	}
	
	
?>