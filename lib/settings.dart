import 'package:shared_preferences/shared_preferences.dart';
import 'dart:async';

class SettingsRepository {
  late SharedPreferences prefs;
  int deviceId = 0;
  String devicePassword = '';
  String deviceName = '';

  Future<bool> loadPrefs() async {
    prefs = await SharedPreferences.getInstance();
    deviceId = prefs.getInt('device_id') ?? 0;
    devicePassword = prefs.getString('device_password') ?? '';
    deviceName = prefs.getString('device_name') ?? '';
    return true;
  }

  Future <void> resetValues() async {
    await prefs.clear().then((value) => {
      loadPrefs()
    });
  }

  void saveDeviceId(int id) async {
    deviceId = id;
    await prefs.setInt('device_id', id);
  }

  void saveDevicePassword(String password) async {
    devicePassword = password;
    await prefs.setString('device_password', password);
  }

  void saveDeviceName(String name) async {
    deviceName = name;
    await prefs.setString('device_name', name);
  }

}