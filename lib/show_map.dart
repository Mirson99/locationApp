import 'package:flutter/material.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'device_model.dart';
import 'constants.dart';

class ShowMap extends StatefulWidget {
  final String title = APP_TITLE;
  final DeviceCredentials deviceCredentials;

  const ShowMap({Key? key, required this.deviceCredentials}) : super(key: key);
  @override
  _MapState createState() => _MapState();
}

class _MapState extends State<ShowMap> {


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
              Container(
                height: 450,
                child:
                    InAppWebView(
                      initialUrlRequest: URLRequest(
                          url: Uri.https(API_URL, API_PATH + '/get_map.php', {'dev_id': widget.deviceCredentials.deviceId.toString(), 'dev_pass':widget.deviceCredentials.devicePassword})
                      ),
                      initialOptions: InAppWebViewGroupOptions(
                        crossPlatform: InAppWebViewOptions(
                          supportZoom: false,
                          javaScriptEnabled: true,
                          disableHorizontalScroll: false,
                          disableVerticalScroll: false,
                        ),
                      ),
                      onReceivedServerTrustAuthRequest: (controller, challenge) async {
                        return ServerTrustAuthResponse(action: ServerTrustAuthResponseAction.PROCEED);
                      },
                    ),
              )
            ],
          ),
        ),


    );
  }
}