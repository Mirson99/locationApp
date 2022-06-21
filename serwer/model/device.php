<?php
require_once('db.php');
require_once('user.php');
require_once('libs/password.php');

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}


//-------------------------------------------
function check_device_name ($device_name){
	if (empty($device_name))
		return "Nazwa urzadzenia nie może byc pusta!";
	
	if (strlen($device_name) < 5)
		return "Nazwa musi sie skladac co najmniej z 5 znakow.";
	
	if (!preg_match("/^[a-zA-Z0-9 _\-]*$/", $device_name))
		return "Nazwa urzadzenia moze zawierac <u>jedynie</u> małe i wielkie litery, cyfry, spację oraz znaki '_' i '-'";
	
	return true;
}

//-------------------------------------------
function add_device ($usr_email, $device_name){
	global $conn;
	
	if(!is_logged_in())
		return array(false, "Zaloguj sie aby dodac urzadzenie!");
	
	$res = check_device_name($device_name);
	if ($res !== true)
		return array(false, $res);

	// generate device password
	$dev_pass = generateRandomString(20);
	$dev_pass_hash = password_hash($dev_pass, PASSWORD_DEFAULT);
	
	
	// get user id...
	$result = $conn->query("SELECT `usr_id` FROM `user` WHERE `usr_mail` = '".$usr_email."' LIMIT 1");
	if ($result->num_rows < 1)
		return array(false, "Brak ID urzytkownika w bazie o.O (".$usr_email.")");
	
	$usr_id = $result->fetch_object()->usr_id;

	// add device to db
	$query = "INSERT INTO `device` (`dev_id`,`dev_pass` ,`usr_id` ,`dev_name`)VALUES (NULL , '".$dev_pass_hash."', '".$usr_id."', '".$device_name."');";
	$result = $conn->query($query);

	if ($result === true) {
		return array(true, $conn->insert_id, $dev_pass);
	}
	
	return array(false, "Mysql Error! (adding device to table)");
}


//-------------------------------------------
function rename_device ($usr_email, $device_id, $new_device_name){
	global $conn;

	if(!is_logged_in())
		return array(false, "Zaloguj się aby zmienić nazwę urządzenia!");
	
	$res = check_device_name($new_device_name);
	if ($res !== true)
		return array(false, $res);
	
	$usr_id = get_user_id($usr_email);

	$query = "UPDATE `device` SET `dev_name` = '".$new_device_name."' WHERE `device`.`dev_id` =".$device_id." AND `device`.`usr_id`='".$usr_id."';";
	$result = $conn->query($query);

	if ($result === true) {
		return array(true, "Zmieniono nazwę urządzenia");
	}
	
	return array(false, "Mysql Error! (renaming device)");
}


//-------------------------------------------
function get_device_name($device_id) {
	global $conn;
	
	$result = $conn->query("SELECT `dev_name` FROM `device` WHERE `dev_id`='".$device_id."' LIMIT 1");
	if ($result->num_rows < 1)
		return "unknown";
	else
		return $result->fetch_object()->dev_name;
}

//-------------------------------------------
function load_device_list($usr_mail) {
	global $conn;
	
	$list = array();
	
	$result = $conn->query("SELECT `usr_id` FROM `user` WHERE `usr_mail` = '".$usr_mail."' LIMIT 1");
	if ($result->num_rows > 0){
		$id = $result->fetch_object()->usr_id;
		
		$result = $conn->query("SELECT `dev_id`, `dev_name`, `last_seen` FROM `device` WHERE `usr_id`='".$id."'");	
		
		$online_time = time() - 300;
		while ($device = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$status = (strtotime($device['last_seen']) > $online_time) ? '<font color="green">ONLINE</font>' : '<font color="red">OFFLINE</font>';
			$list[] = array('name'=>$device['dev_name'], 'id' => $device['dev_id'], 'status'=>$status);
		}
	}
	
	
	return $list;
}


//-------------------------------------------
function delete_device($id, $usr_email) {
	global $conn;

	$usr_id = get_user_id($usr_email);
	
	$res = $conn->query("DELETE FROM `device` WHERE `dev_id` = '".$id."' AND `usr_id`= '".$usr_id."' LIMIT 1");
	if($res === true) {
		$conn->query("DELETE FROM `location` WHERE `dev_id` = '".$id."'");
	}
}


//-------------------------------------------
function check_device_credentials($device_id, $device_password) {
	global $conn;

	if (empty($device_id) || empty($device_password)) {
		return false;
	}
	
	$device_id = addslashes(substr($device_id, 0, 255));
	$device_password = addslashes(substr($device_password, 0, 255));

	$result = $conn->query("SELECT `dev_pass` FROM `device` WHERE `dev_id` = '".$device_id."' LIMIT 1");
	if ($result->num_rows < 1)
		return false;
	
	
	$dev_data = $result->fetch_object();
	if (password_verify($device_password, $dev_data->dev_pass)) {
		return true;
	}
	
	return false;
}
?>