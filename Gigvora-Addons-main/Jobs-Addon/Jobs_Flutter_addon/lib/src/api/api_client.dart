import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:meta/meta.dart';

/// Lightweight API client tailored for the Laravel jobs package endpoints.
class JobsApiClient {
  JobsApiClient({
    required this.baseUrl,
    http.Client? client,
    Map<String, String>? defaultHeaders,
    this.timeout = const Duration(seconds: 30),
  })  : _client = client ?? http.Client(),
        _defaultHeaders = {
          'Content-Type': 'application/json',
          if (defaultHeaders != null) ...defaultHeaders,
        };

  final String baseUrl;
  final http.Client _client;
  final Map<String, String> _defaultHeaders;
  final Duration timeout;

  Uri _uri(String path, [Map<String, String>? query]) {
    final base = Uri.parse(baseUrl);
    final cleanedPath = path.startsWith('/') ? path : '/$path';
    return base.replace(path: cleanedPath, queryParameters: query);
  }

  Future<Map<String, dynamic>> getJson(String path,
      {Map<String, String>? query, Map<String, String>? headers}) async {
    final response = await _client
        .get(_uri(path, query), headers: {..._defaultHeaders, ...?headers})
        .timeout(timeout);
    _throwIfInvalid(response);
    return _decode(response);
  }

  Future<Map<String, dynamic>> postJson(String path,
      {Map<String, String>? query,
      Map<String, dynamic>? body,
      Map<String, String>? headers}) async {
    final response = await _client
        .post(
          _uri(path, query),
          headers: {..._defaultHeaders, ...?headers},
          body: jsonEncode(body ?? <String, dynamic>{}),
        )
        .timeout(timeout);
    _throwIfInvalid(response);
    return _decode(response);
  }

  Future<Map<String, dynamic>> putJson(String path,
      {Map<String, dynamic>? body, Map<String, String>? headers}) async {
    final response = await _client
        .put(
          _uri(path),
          headers: {..._defaultHeaders, ...?headers},
          body: jsonEncode(body ?? <String, dynamic>{}),
        )
        .timeout(timeout);
    _throwIfInvalid(response);
    return _decode(response);
  }

  Future<void> delete(String path, {Map<String, String>? headers}) async {
    final response = await _client
        .delete(_uri(path), headers: {..._defaultHeaders, ...?headers})
        .timeout(timeout);
    _throwIfInvalid(response);
  }

  @visibleForTesting
  void dispose() => _client.close();

  void _throwIfInvalid(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return;
    }
    throw JobsApiException(
      statusCode: response.statusCode,
      body: response.body,
    );
  }

  Map<String, dynamic> _decode(http.Response response) {
    final decoded = jsonDecode(response.body);
    if (decoded is Map<String, dynamic>) {
      return decoded;
    }
    throw const JobsApiException(statusCode: 500, body: 'Unexpected payload');
  }
}

class JobsApiException implements Exception {
  const JobsApiException({required this.statusCode, required this.body});

  final int statusCode;
  final String body;

  @override
  String toString() => 'JobsApiException(statusCode: $statusCode, body: $body)';
}
