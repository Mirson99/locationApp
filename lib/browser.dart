import 'package:flutter_inappwebview/flutter_inappwebview.dart';

class MyInAppBrowser extends InAppBrowser {
  @override
  Future<ServerTrustAuthResponse?>? onReceivedServerTrustAuthRequest(challenge) async {
    return ServerTrustAuthResponse(action: ServerTrustAuthResponseAction.PROCEED);
  }
}