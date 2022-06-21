<?php
	require_once('db.php');
	require_once('user.php');
	
	
//----------------------------------
function get_points($usr_email, $device_id, $startTime, $endTime) {
	global $conn;
	
	$points = array();

	$device_id = addslashes($device_id);
	$startTime = addslashes($startTime);
	$endTime = addslashes($endTime);
	
	
	// Check if user can obtain data (is device his)
	$usr_id = get_user_id($usr_email);
	$result = $conn->query("SELECT `dev_id` FROM `device` WHERE `usr_id` = '".$usr_id."' AND `dev_id` = '".$device_id."' LIMIT 1");
	if ($result->num_rows > 0) {

		$query = "SELECT `loc_time`, `loc_latitude`, `loc_longtitude` FROM `location` WHERE `dev_id` = '".$device_id
				."' AND `loc_time` >= FROM_UNIXTIME(".$startTime.") AND `loc_time` <= FROM_UNIXTIME(".$endTime.") ORDER BY `loc_time`";
		$result = $conn->query($query);

		while ($pt = $result->fetch_array(MYSQLI_NUM)) {
			$points[] = array($pt[1], $pt[2], $pt[0]);
		}
	}
	
	return $points;
}

//----------------------------------
function get_points_mobile_client($device_id, $startTime, $endTime) {
	global $conn;
	
	$points = array();

	$device_id = addslashes($device_id);
	$startTime = addslashes($startTime);
	$endTime = addslashes($endTime);
	
	
	$query = "SELECT `loc_time`, `loc_latitude`, `loc_longtitude` FROM `location` WHERE `dev_id` = '".$device_id
			."' AND `loc_time` >= FROM_UNIXTIME(".$startTime.") AND `loc_time` <= FROM_UNIXTIME(".$endTime.") ORDER BY `loc_time`";
	$result = $conn->query($query);

	while ($pt = $result->fetch_array(MYSQLI_NUM)) {
		$points[] = array($pt[1], $pt[2], $pt[0]);
	}
	
	return $points;
}


//----------------------------------
function points_to_geoJSON($points) {
	if (count($points)<1) {
		return 'var newGeojsonFeature = {"type": "MultiLineString", "coordinates": []};';
	}
	// Important geoJSON cords are y, x not x, y so need to be swaped
	// add first point as feature
	$geo = '
			var newGeojsonFeature = {
			  "type": "FeatureCollection",
			  "features": [
			  {
				"type": "Feature",
				"properties": {
					"name": "Start",
					"popupContent": "Początek trasy <br />'.$points[0][2].'"
				},
				"geometry": {
					"type": "Point",
					"coordinates": ['.$points[0][1].', '.$points[0][0].']
				}
				  
			  },
			  {
				"type": "LineString",
				"properties": {
						"name": "Trasa",
						"popupContent": "Koniec trasy<br/>'.$points[count($points)-1][2].'"
					},
				"coordinates": [';
	
	foreach ($points as $p) {
		$geo .= '['.$p[1].', '.$p[0].'],';
	}
	
	// usuwam ostatni przecinek...
	$geo = rtrim($geo, ", ");
	
	// add last point as feature
	$geo .= ']
				}, 
				{
					"type": "Feature",
					"properties": {
						"name": "End",
						"popupContent": "Koniec trasy<br/>'.$points[count($points)-1][2].'"
					},
					"geometry": {
						"type": "Point",
						"coordinates": ['.$points[count($points)-1][1].', '.$points[count($points)-1][0].']
					}
					  
				  }
				]
			};';
			

	return $geo;
}

?>