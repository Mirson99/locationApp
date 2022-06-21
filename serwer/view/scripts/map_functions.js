var today = new Date();
time = ('' + today.getHours()).padStart(2, '0') + ':' + ('' + today.getMinutes()).padStart(2, '0');
data = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(Math.max(today.getDate()-1,1)).padStart(2, '0');
data2 = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

document.getElementById("dateStart").value = data;
document.getElementById("timeStart").value = '00:00';
document.getElementById("dateEnd").value = data2;
document.getElementById("timeEnd").value = time;

var last_el = null;
//-------------------------------
function change_selection (el) {
	if (last_el != null) {
		last_el.classList.remove('_dark');
	}
	el.classList.add('_dark');
	last_el = el;								
}


var myLayer = null;
//-------------------------------
function pokaz_dane_na_mapie (geoJSON) {
	myLayer = L.geoJSON().bindPopup(function (layer) {
				return layer.feature.properties.popupContent;
			}).addTo(map);
	eval(geoJSON);

	myLayer.addData(newGeojsonFeature);
	map.fitBounds(myLayer.getBounds());
	//alert('aded to map');
}

//-------------------------------
function zaladuj_dane_gps (el, start, end) {
	change_selection(el);
	
	start = Math.floor(start / 1000);
	end = Math.floor(end / 1000);

	if(myLayer != null) {
		map.removeLayer(myLayer);
	}
	
	var on_mobile = false;
	var device_id = document.getElementById('device_id').value;
	var dev_password_input = document.getElementById('device_password');
	// check if running on mobile client
	var device_password = '';
	if (dev_password_input != null) {
		on_mobile = true;
		device_password = dev_password_input.value;
	}


	// Ajax
	var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
           if (xmlhttp.status == 200) {
               pokaz_dane_na_mapie(xmlhttp.responseText);
           }
           else if (xmlhttp.status == 400) {
              alert('Błąd ładowania danych (400)');
           }
           else {
               alert('Błąd ładowania danych: ' + xmlhttp.status);
           }
        }
    };

	if (on_mobile == true) {
		xmlhttp.open("GET", "getLocations.php?mobile=1&id=" + device_id + "&start=" + start + "&end=" + end + "&device_password=" + device_password, true);
	} else {
		xmlhttp.open("GET", "client/getLocations.php?id=" + device_id + "&start=" + start + "&end=" + end, true);
	}
    xmlhttp.send();

}

//-------------------------------
function sh_dni(el, il_dni){
	var d = new Date();
	zaladuj_dane_gps(el, d.valueOf() - (1000 * 3600 * 24 * il_dni), d.valueOf());
}

//-------------------------------
function swap_dates() {
	var start = document.getElementById("dateStart");
	var end = document.getElementById("dateEnd")
	var tmp = start.value;
	start.value = end.value;
	end.value = tmp;
	
	start = document.getElementById("timeStart");
	end = document.getElementById("timeEnd")
	tmp = start.value;
	start.value = end.value;
	end.value = tmp;
}

//-------------------------------
function sh_okres(el){
	
	tmp_d = document.getElementById("dateStart").value;
	tmp_t = document.getElementById("timeStart").value;			
	var start = Date.parse(tmp_d + ' ' + tmp_t);								

	tmp_d = document.getElementById("dateEnd").value;
	tmp_t = document.getElementById("timeEnd").value;			
	var end = Date.parse(tmp_d + ' ' + tmp_t);
	
	if (start.valueOf() > end.valueOf()) {
		swap_dates();
		sh_okres(el);
	} else {
		zaladuj_dane_gps(el, start.valueOf(), end.valueOf());
	}
}