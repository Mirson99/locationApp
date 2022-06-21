<?php
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	
		// dane mysql dla hostingu
		$servername = "";
		$username = "";
		$password = "";
		$db_name = "";

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
	  die("MySQL Connection failed!!!<br/>Error: " . $conn->connect_error);
	}
	
	$conn->select_db($db_name);

?>