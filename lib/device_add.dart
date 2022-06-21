import 'dart:convert';
import 'package:flutter/material.dart';
import 'user.dart';
import 'device_model.dart';
import 'package:http/http.dart' as http;
import 'constants.dart';


Future<RegisterDeviceResult> fetchDeviceRegisterResult(email, password, deviceName) async {
  Uri url = Uri.https(API_URL, API_PATH + '/add_device.php', {'email': email, 'password':password, 'device_name' : deviceName});
  final response = await http.get(url);

  if (response.statusCode == 200) {
    RegisterDeviceResult devRes = RegisterDeviceResult.fromJson(jsonDecode(response.body));
    return devRes;
  } else {
    throw Exception('Nie można połączyć z serwerem (dodawanie urządzenia)');
  }
}


class RegisterDevice extends StatefulWidget {
  final UserCredentials userCredential;

  const RegisterDevice({Key? key, required this.userCredential}) : super(key: key);
  @override
  _RegisterDeviceState createState() => _RegisterDeviceState();
}

class _RegisterDeviceState extends State<RegisterDevice> {
  final deviceNameController = TextEditingController();
  late RegisterDeviceResult registerResult;
  bool loadingInProgress = false;

  @override
  void dispose() {
    // Clean up the controller when the widget is disposed.
    deviceNameController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Location App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: Material(
        child: Column(
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.only(top: 40.0),
              child: Center(
                child: SizedBox(
                    width: 200,
                    height: 120,
                    child: Image.asset('assets/logo.png')),
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 15,vertical:20.0),
              child: TextField(
                controller: deviceNameController,
                decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Nazwa urządzenia',
                    hintText: 'Podaj nazwę dla urządzenia'),
              ),
            ),

            if (loadingInProgress == false)
              Container(
              height: 50,
              width: 250,
              decoration: BoxDecoration(
                  color: Colors.blue, borderRadius: BorderRadius.circular(20)),
              child: TextButton(
                // register
                onPressed: () async {
                  setState(() { loadingInProgress = true; });
                  registerResult = await fetchDeviceRegisterResult(widget.userCredential.getEmail,
                                                                    widget.userCredential.getPassword, deviceNameController.text);
                  setState(() { loadingInProgress = false; });

                  if (registerResult.isSuccess()) {

                    Navigator.pop(context, DeviceCredentials(registerResult.deviceId, registerResult.devicePassword, deviceNameController.text));
                  } else {
                    showDialog(
                      context: context,
                      builder: (context) {
                        return AlertDialog(
                          content: Text(registerResult.errorMessage()),
                          backgroundColor: Colors.redAccent,
                        );
                      },
                    );

                  }
                },
                child: const Text(
                  'Dodaj',
                  style: TextStyle(color: Colors.white, fontSize: 25),
                ),
              ),
            ),

            if (loadingInProgress == true)
              Container(
                  alignment: Alignment.topCenter,
                  margin: const EdgeInsets.only(top: 20),
                  child: const CircularProgressIndicator(
                    backgroundColor: Colors.grey,
                    color: Colors.blue,
                    strokeWidth: 10,
                  )
              ),


            const SizedBox(
              height: 130,
            ),
          ],
        ),
      ),
    );
  }
}