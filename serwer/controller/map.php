<?php
	require_once('model/db.php');
	require_once('model/device.php');
	$page_help = 'Aby pokazać trasę urządzenia na mapie wybierz porządany okres czasowy
	';
	
	$device_id = $_GET['id'];
	$device_name = get_device_name($device_id);
	$page_title = "Historia lokalizacji urządzenia (".$device_name.")";

	require('view/layout/header.php');
	require('view/layout/top_menu_loggd.php');
	
	echo "\n<!-- for js loader-->\n".'<input id="device_id" value="'.$device_id.'" type="hidden">'."\n";

	if (!is_logged_in()){
		die("Nie zalogowano !");
	}
	
	
	require_once('view/map.php');
	
?>
