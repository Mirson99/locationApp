class DeviceCredentials {
  final int deviceId;
  final String devicePassword;
  final String deviceName;

  const DeviceCredentials(this.deviceId, this.devicePassword, this.deviceName);

  int get getDeviceId{
    return deviceId;
  }

  String get getDevicePassword{
    return devicePassword;
  }

  String get getDeviceName{
    return deviceName;
  }

}

class RegisterDeviceResult {
  final int result;
  final int deviceId;
  final String devicePassword;

  bool isSuccess() {
    return result == 1 ? true : false;
  }

  int getDeviceId() {
    return result == 1 ? deviceId : 0;
  }

  String getDevicePassword() {
    return result == 1 ? devicePassword : '';
  }

  String errorMessage() {
    if (result == 0) {
      return devicePassword;
    }
    return '';
  }

  RegisterDeviceResult(this.result, this.deviceId, this.devicePassword);

  RegisterDeviceResult.fromJson(Map<String, dynamic> json)
      : result = json['result'],
        deviceId = json['deviceId'],
        devicePassword = json['devicePassword'];

  Map<String, dynamic> toJson() => {
    'result': result,
    'deviceId': deviceId,
    'devicePassword': devicePassword,
  };
}