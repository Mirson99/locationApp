<?php
	
	//--------------------------------------
	// obsługa sesji
	session_start();
			
	if ((!isset($_SESSION['inited'])) || ($_SESSION['inited']!='7'))
	{
	  session_regenerate_id();
	  $_SESSION['inited'] = '7';
	  $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	}
			
	require_once('controller/main.php');

?>