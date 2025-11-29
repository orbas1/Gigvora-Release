class ApiException implements Exception {
  final int? statusCode;
  final String message;
  final Map<String, dynamic>? details;

  ApiException(this.message, {this.statusCode, this.details});

  @override
  String toString() =>
      'ApiException(statusCode: ' + (statusCode?.toString() ?? 'null') + ', message: ' + message + ')';
}
