import 'dart:convert';
import 'package:http/http.dart' as http;

typedef TokenProvider = Future<String?> Function();

typedef JsonMap = Map<String, dynamic>;

typedef JsonList = List<dynamic>;

class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => 'ApiException($statusCode): $message';
}

class BaseApiService {
  final String baseUrl;
  final TokenProvider? tokenProvider;
  final http.Client _client;

  BaseApiService({
    required this.baseUrl,
    this.tokenProvider,
    http.Client? client,
  }) : _client = client ?? http.Client();

  Uri _uri(String path) => Uri.parse('$baseUrl$path');

  Uri _uriWithQuery(String path, Map<String, dynamic> query) =>
      _uri(query.isEmpty ? path : '$path?${Uri(queryParameters: query.map((k, v) => MapEntry(k, v.toString()))).query}');

  Future<Map<String, String>> _headers() async {
    final token = await tokenProvider?.call();
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  dynamic _decodeBody(http.Response response) {
    if (response.body.isEmpty) {
      return {};
    }
    return jsonDecode(response.body);
  }

  dynamic _handleResponse(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return _decodeBody(response);
    }
    throw ApiException(
      'Request failed with status ${response.statusCode}',
      statusCode: response.statusCode,
    );
  }

  Future<dynamic> get(String path) async {
    final response = await _client.get(_uri(path), headers: await _headers());
    return _handleResponse(response);
  }

  Future<dynamic> getWithQuery(String path, Map<String, dynamic> query) async {
    final response =
        await _client.get(_uriWithQuery(path, query), headers: await _headers());
    return _handleResponse(response);
  }

  Future<dynamic> post(
    String path, {
    Map<String, dynamic>? data,
    String method = 'POST',
  }) async {
    final response = await _client.post(
      _uri(path),
      headers: await _headers(),
      body: data != null ? jsonEncode(data) : null,
    );

    if (method != 'POST') {
      // For methods like DELETE emulated via POST we still reuse post.
    }
    return _handleResponse(response);
  }

  Future<dynamic> delete(String path, {Map<String, dynamic>? data}) async {
    final response = await _client.delete(
      _uri(path),
      headers: await _headers(),
      body: data != null ? jsonEncode(data) : null,
    );
    return _handleResponse(response);
  }
}
