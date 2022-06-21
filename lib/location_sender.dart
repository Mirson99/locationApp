import 'constants.dart';
import 'package:http/http.dart' as http;
import 'dart:core';


class LocationSender {
  static final LocationSender _singleton = LocationSender._internal();

  factory LocationSender() {
    return _singleton;
  }
  LocationSender._internal();

  double lastLon = 0;
  double lastLat = 0;

  Future<bool> saveLocation(int deviceId, String devicePassword, double lon, double lat) async {

    double v_x = lat - lastLat;
    double v_y = lon - lastLon;

    if (v_x.abs() < 0.005 && v_y.abs() < 0.005) {
      return true;
    }


    Uri url = Uri.https(API_URL, API_PATH + '/save_location.php', {'dev_id': deviceId.toString(), 'dev_pass':devicePassword,
                                                                        'lat': lat.toString(), 'lon':lon.toString()});
    final response = await http.get(url);

    if (response.statusCode == 200) {
      if (response.body == 'ok') {
        lastLat = lat;
        lastLon = lon;
        return true;
      }
    }
    return false;
  }
}