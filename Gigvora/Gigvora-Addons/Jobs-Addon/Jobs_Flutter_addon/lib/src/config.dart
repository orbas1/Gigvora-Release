class JobsAddonConfig {
  JobsAddonConfig._();

  static String baseUrl = '';
  static String? authToken;

  static Map<String, String> defaultHeaders() => {
        if (authToken != null) 'Authorization': 'Bearer $authToken',
      };
}
