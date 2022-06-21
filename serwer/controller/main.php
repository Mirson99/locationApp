<?php
	require_once('model/db.php');
	require_once('model/user.php');

	if ($_GET['a']== 'log_out') {
		log_out();
	}

	$_GET['id'] = addslashes($_GET['id']);

	//-----------------------------------------------------
	// Urzytkownik nie jest zalogowany
	if (!is_logged_in()) {
		switch ($_GET['page']) {
			case 'register':
				require_once('register.php');
			break;
			
			default:
				require_once('login.php');
		}
	}
	
	//-----------------------------------------------------
	// Urzytkownik JEST zalogowany		
	if (is_logged_in()) {
		$page_title = "Zalogowany";
		
		switch ($_GET['page']) {
			case 'devices':
				require_once('devices.php');
			break;

			case 'account':
				require_once('account.php');
			break;
			
			case 'map':
				require_once('map.php');
			break;

			default:
				require_once('devices.php');
		}
		
		require('view/layout/footer.php');
	}

?>