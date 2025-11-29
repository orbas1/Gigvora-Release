import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiClient {
  ApiClient({required this.baseUrl, required this.token});

  final String baseUrl;
  final String Function()? token;

  Map<String, String> get _headers => {
        'Content-Type': 'application/json',
        if (token?.call() != null) 'Authorization': 'Bearer ${token!.call()}',
      };

  Future<dynamic> get(String path) async {
    final res = await http.get(Uri.parse('$baseUrl$path'), headers: _headers);
    return _parse(res);
  }

  Future<dynamic> post(String path, Map<String, dynamic> body) async {
    final res = await http.post(Uri.parse('$baseUrl$path'), headers: _headers, body: jsonEncode(body));
    return _parse(res);
  }

  dynamic _parse(http.Response res) {
    if (res.statusCode >= 200 && res.statusCode < 300) {
      return res.body.isNotEmpty ? jsonDecode(res.body) : {};
    }
    throw Exception('Request failed: ${res.statusCode}');
  }
}
