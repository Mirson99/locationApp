import 'dart:io';
import 'package:location/location.dart';
import 'package:background_location/background_location.dart' as bg_geo;
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'constants.dart';
import 'settings.dart';
import 'user.dart';
import 'device_model.dart';
import 'device_add.dart';
import 'show_map.dart';
import 'location_sender.dart';




void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  ByteData data = await PlatformAssetBundle().load('assets/ca/cert.pem');
  SecurityContext.defaultContext.setTrustedCertificatesBytes(data.buffer.asUint8List());

  if (Platform.isAndroid) {
    await AndroidInAppWebViewController.setWebContentsDebuggingEnabled(true);
  }
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  MyApp({Key? key}) : super(key: key);

  final String appTitle = APP_TITLE;
  final Location location = Location();


  @override
  Widget build(BuildContext context) {
    return MaterialApp(
          title: appTitle,
          debugShowCheckedModeBanner: false,
          theme: ThemeData(
            primarySwatch: Colors.green,
          ),
          home: MyHomePage(title: appTitle),
        );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({Key? key, required this.title}) : super(key: key);

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  AppLifecycleState? _notification;
  String deviceRegisteredName = '';
  int deviceId = 0;
  String devicePassword = '';
  bool locationAccesible = true;
  Location location = Location();
  String locationString = '';
  LocationSender locationSender = LocationSender();
  bool backgroundServiceOn = false;

  late SettingsRepository sr;

  void loadSettings () async {
    sr = SettingsRepository();
    sr.loadPrefs().then((loadStatus) {
      setState(() {
        deviceRegisteredName = sr.deviceName;
        deviceId = sr.deviceId;
        devicePassword = sr.devicePassword;
      });
    });
  }

  //------------------------------------------------------------------
  void checkLocationPermissions() async {

    bool _serviceEnabled;
    PermissionStatus _permissionGranted;

    _serviceEnabled = await location.serviceEnabled();
    if (!_serviceEnabled) {
      _serviceEnabled = await location.requestService();
      if (!_serviceEnabled) {
        showDialog(
          context: context,
          builder: (context) {
            return const AlertDialog(
              content: Text('Włącz Lokalizację'),
              backgroundColor: Colors.redAccent,
            );
          },
        );
        return;
      }
    }

    _permissionGranted = await location.hasPermission();
    if (_permissionGranted == PermissionStatus.denied) {
      _permissionGranted = await location.requestPermission();
      if (_permissionGranted != PermissionStatus.granted) {
        showDialog(
          context: context,
          builder: (context) {
            return const AlertDialog(
              content: Text('Aplikacja wymaga uprawnień do odczytu lokalizacji!'),
              backgroundColor: Colors.redAccent,
            );
          },
        );

        setState(() {locationAccesible = false;});
        return;
      }
    }

    //--------------------------------
    // location services available  - start background service

    if (backgroundServiceOn == false) {
      bg_geo.BackgroundLocation.setAndroidNotification(
        title: APP_TITLE,
        message: "Zbieranie lokalizacji",
        icon: 'assets/logo.png',
      );
      bg_geo.BackgroundLocation.setAndroidConfiguration(
          UPDATE_LOCATION_DELAY_MS);
      bg_geo.BackgroundLocation.startLocationService();
      bg_geo.BackgroundLocation.startLocationService(
          distanceFilter: MIN_DISTANCE_TO_CARE);
      bg_geo.BackgroundLocation.startLocationService(
          forceAndroidLocationManager: true);
      bg_geo.BackgroundLocation.getLocationUpdates((location) {
        double lon = location.longitude ?? 0;
        double lat = location.latitude ?? 0;

        if (deviceId != 0 && devicePassword.isNotEmpty &&
            (lon != 0 || lat != 0)) {
          locationSender.saveLocation(deviceId, devicePassword, lon, lat);
        }
      });
      backgroundServiceOn = true;
      setState(() {backgroundServiceOn = true;});
    }

    //----------------------------------------
    // foreground location
    LocationData _locationData;
    _locationData = await location.getLocation();

    setState(() {
      locationAccesible = true;
      double lon = _locationData.longitude ?? 0;
      double lat = _locationData.latitude ?? 0;
      locationString = lon.toStringAsFixed(3) + ' ' + lat.toStringAsFixed(3);
    });

  }

  @override
  void initState() {
    super.initState();
    loadSettings();
    checkLocationPermissions();
  }

  @override
  void dispose() {
    super.dispose();
  }

  Future<void> openUserLogin(BuildContext context) async {
    try {
      final UserCredentials userCredentials = await Navigator.push(
        context,
        MaterialPageRoute(builder: (context) => LoginUser()),
      );

      if (userCredentials != null) {
        final DeviceCredentials deviceCredentials = await Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => RegisterDevice(userCredential: userCredentials)),
        );

        setState(() {
            deviceId = deviceCredentials.getDeviceId;
            devicePassword = deviceCredentials.getDevicePassword;
            deviceRegisteredName = deviceCredentials.getDeviceName;
        });

        showDialog(
          context: context,
          builder: (context) {
            return const AlertDialog(
              content: Text('Dodano urządzenie do konta użytkownika :)'),
              backgroundColor: Colors.greenAccent,
            );
          },
        );

        setState(() {
          sr.saveDeviceId(deviceCredentials.getDeviceId);
          sr.saveDevicePassword(deviceCredentials.getDevicePassword);
          sr.saveDeviceName(deviceCredentials.getDeviceName);
        });
      }
    } catch (error) {
      showDialog(
        context: context,
        builder: (context) {
          return const AlertDialog(
            content: Text('Wystąpił bład logowania'),
            backgroundColor: Colors.redAccent,
          );
        },
      );
    }


  }

  deleteDevice(BuildContext context) {
    BuildContext dialogContext = context;

    // set up the buttons
    Widget cancelButton = TextButton(
      style: ButtonStyle(backgroundColor: MaterialStateProperty.all(Colors.white)),
      child: const Text("Nie"),
      onPressed:  () {
        Navigator.pop(dialogContext);
      },
    );
    Widget continueButton = TextButton(
      child: const Text("Tak"),
      style: ButtonStyle(backgroundColor: MaterialStateProperty.all(Colors.white)),
      onPressed:  () async {

        // Login to confirm
          try {
            final UserCredentials userCredentials = await Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => LoginUser()),
            );

            if (userCredentials.email.isNotEmpty) {
              Navigator.pop(dialogContext);
              // Usuwanie prefsów
              sr.resetValues().then((value) =>
              {
                setState(() {
                  deviceId = 0;
                  deviceRegisteredName = '';
                  devicePassword = '';
                })
              });
            }
          } catch (error) {
            showDialog(
              context: context,
              builder: (context) {
                return const AlertDialog(
                  content: Text('Wystąpił bład logowania - nie można usunąć urządzenia z konta'),
                  backgroundColor: Colors.redAccent,
                );
              },
            );
          }


      },
    );
    // set up the AlertDialog
    AlertDialog alert = AlertDialog(
      title: const Text("Usuwanie urządzenia"),
      content: const Text("Czy na pewno chcesz usunąć to urządzenie ze swojego konta użytkownika?"),
      backgroundColor: Colors.redAccent,
      actions: [
        cancelButton,
        continueButton,
      ],
    );
    // show the dialog
    showDialog(
      context: context,
      builder: (BuildContext context) {
        dialogContext = context;
        return alert;
      },
    );
  }



  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.title),
        actions: [
          IconButton(
            icon: Image.asset('assets/logo.png'),
            onPressed: () => () {},
          ),
        ],
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          children: <Widget>[

            if (deviceRegisteredName.isEmpty)
              Column(
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[
                  const SizedBox(height: 20),
                  Container(
                    height: 70,
                    width: 200,
                    child: const Text('Aby korzystać z aplikacji należy dodać urządzenie do swojego konta użytkownika.',
                      style: TextStyle(fontSize: 15),
                    ),
                  ),
                  const SizedBox(height: 20),
                  Container(
                    height: 50,
                    width: 200,
                    decoration: BoxDecoration(
                        color: Colors.blue, borderRadius: BorderRadius.circular(20)),
                    child: TextButton(
                      onPressed: () {
                        openUserLogin(context);
                      },
                      child: const Text(
                        'Zarejestruj urządzenie',
                        style: TextStyle(color: Colors.white, fontSize: 15),
                      ),
                    ),
                  ),
                ],
              ),

        if (deviceRegisteredName.isNotEmpty)
          Column(
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[

                const SizedBox(height: 20),
                Container(
                  height: 50,
                  width: 200,
                  decoration: BoxDecoration(
                      color: Colors.blue, borderRadius: BorderRadius.circular(20)),
                  child: TextButton(
                      onPressed: () {
                          deleteDevice(context);
                      },
                    child: const Text(
                      'Usuń urządzenie z konta',
                      style: TextStyle(color: Colors.white, fontSize: 15),
                    ),
                  ),
                ),

                const SizedBox(height: 20),
                Container(
                  height: 50,
                  width: 200,
                  decoration: BoxDecoration(
                      color: Colors.blue, borderRadius: BorderRadius.circular(20)),
                  child: TextButton(
                    onPressed: () {
                      DeviceCredentials devCredentials = DeviceCredentials(deviceId, devicePassword, deviceRegisteredName);

                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => ShowMap(deviceCredentials: devCredentials)),
                      );                    },
                    child: const Text(
                      'Pokaż trasę',
                      style: TextStyle(color: Colors.white, fontSize: 15),
                    ),
                  ),
                ),

                // Nazwa urządzenia
                  const SizedBox(height: 80),
                  Container(
                    padding: const EdgeInsets.all(5),
                    decoration: BoxDecoration( color: Colors.cyanAccent, border: Border.all(color: Colors.blueAccent)),
                    child: Text(
                      'Nazwa urządzenia: \n' + deviceRegisteredName,
                      style: const TextStyle(color: Colors.black, fontSize: 15),
                    ),
                  ),
                ],
              ), // Nazwa urządzenia

            // Lokalizacja urządzenia
            if (locationAccesible & locationString.isNotEmpty)
              Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  children: <Widget>[
                    const SizedBox(height: 20),
                    Container(
                      padding: const EdgeInsets.all(5),
                      decoration: BoxDecoration( color: Colors.cyanAccent, border: Border.all(color: Colors.blueAccent)),

                      child: Text(
                        'Współrzędne GPS: \n' + locationString,
                        style: const TextStyle(color: Colors.black, fontSize: 15),
                      ),
                    ),
                  ]
              )



          ],
        ),
      ),


    );
  }

}
