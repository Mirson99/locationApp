import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:flutter_inappwebview/flutter_inappwebview.dart';
import 'package:http/http.dart' as http;
import 'constants.dart';
import 'browser.dart';

class UserCredentials {
  final String email;
  final String password;

  const UserCredentials(this.email, this.password);

  String get getEmail{
    return email;
  }

  String get getPassword{
    return password;
  }
}

class LoginResult {
  final int result;
  final String errorText;

  bool isSuccess() {
    return result == 1 ? true : false;
  }

  String errorMessage() {
    return errorText;
  }

  LoginResult(this.result, this.errorText);

  LoginResult.fromJson(Map<String, dynamic> json)
      : result = json['result'],
        errorText = json['errorText'];

  Map<String, dynamic> toJson() => {
    'result': result,
    'errorText': errorText,
  };
}

Future<LoginResult> fetchLoginResult(email, password) async {
  Uri url = Uri.https(API_URL, API_PATH + '/check_credentials.php', {'mail': email, 'password':password});
  final response = await http.get(url);

  if (response.statusCode == 200) {
    LoginResult logRes = LoginResult.fromJson(jsonDecode(response.body));
    return logRes;
  } else {
    throw Exception('Nie można połączyć z serwerem - logowanie');
  }
}


class LoginUser extends StatefulWidget {
  LoginUser({Key? key}) : super(key: key);
  final MyInAppBrowser browser = new MyInAppBrowser();

  @override
  _LoginUserState createState() => _LoginUserState();
}


class _LoginUserState extends State<LoginUser> {
  final emailController = TextEditingController();
  final passwordController = TextEditingController();
  late LoginResult loginResult;
  bool loadingInProgress = false;

  var options = InAppBrowserClassOptions(
      crossPlatform: InAppBrowserOptions(hideUrlBar: false),
      inAppWebViewGroupOptions: InAppWebViewGroupOptions(
          crossPlatform: InAppWebViewOptions(javaScriptEnabled: true)));

  @override
  void dispose() {
    // Clean up the controller when the widget is disposed.
    emailController.dispose();
    passwordController.dispose();
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
        child: Scaffold (
          body: SingleChildScrollView(
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
                padding: const EdgeInsets.symmetric(horizontal: 15),
                child: TextField(
                  controller: emailController,
                  decoration: const InputDecoration(
                      border: OutlineInputBorder(),
                      labelText: 'Email',
                      hintText: 'Wprowadź poprawny adres email'),
                ),
              ),
              Padding(
                padding: const EdgeInsets.only(
                    left: 15.0, right: 15.0, top: 15, bottom: 0),
                //padding: EdgeInsets.symmetric(horizontal: 15),
                child: TextField(
                  controller: passwordController,
                  obscureText: true,
                  decoration: const InputDecoration(
                      border: OutlineInputBorder(),
                      labelText: 'Password',
                      hintText: 'Wpisz hasło użytkownika'),
                ),
              ),
                TextButton(
                  onPressed: () {
                    Uri _registerUrl = Uri.https(API_URL, 'index.php', {'page': 'register'});
                    widget.browser.openUrlRequest(

                        urlRequest: URLRequest(url: _registerUrl),
                        options: options);
                  },
                  child: const Text(
                    'Zarejestruj się',
                    style: TextStyle(color: Colors.blue, fontSize: 15),
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

              if (loadingInProgress == false)
                Container(
                  height: 50,
                  width: 250,
                  decoration: BoxDecoration(
                      color: Colors.blue, borderRadius: BorderRadius.circular(20)),

                  child: TextButton(
                    // go to login...

                    onPressed: () async {

                      setState(() { loadingInProgress = true; });

                      loginResult = await fetchLoginResult(
                          emailController.text, passwordController.text);

                      setState(() { loadingInProgress = false; });

                      if (loginResult.isSuccess()) {
                        Navigator.pop(context, UserCredentials(
                            emailController.text, passwordController.text));
                      } else {
                        showDialog(
                          context: context,
                          builder: (context) {
                            return AlertDialog(
                              content: Text(loginResult.errorMessage()),
                              backgroundColor: Colors.redAccent,
                            );
                          },
                        );
                      }
                    },
                    child: const Text(
                      'Login',
                      style: TextStyle(color: Colors.white, fontSize: 25),
                    ),
                  ),
                ),

            const SizedBox(
              height: 130,
            ),
            ],
          ),
        ),
        )
      ),
    );
  }
}