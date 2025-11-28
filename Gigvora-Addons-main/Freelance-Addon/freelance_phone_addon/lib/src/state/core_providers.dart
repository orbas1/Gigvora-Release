import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:http/http.dart' as http;

import '../api/freelance_api_client.dart';
import '../repositories/freelance_repository.dart';

final baseUrlProvider = Provider<String>((ref) => 'https://BASE_URL.com/api/');

final tokenProviderOverride = Provider<String Function()>((ref) => () => '');

final httpClientProvider = Provider<http.Client>((ref) => http.Client());

final apiClientProvider = Provider<FreelanceApiClient>((ref) {
  final baseUrl = ref.watch(baseUrlProvider);
  final tokenProvider = ref.watch(tokenProviderOverride);
  return FreelanceApiClient(
    baseUrl: baseUrl,
    httpClient: ref.watch(httpClientProvider),
    tokenProvider: tokenProvider,
  );
});

final freelanceRepositoryProvider = Provider<FreelanceRepository>((ref) {
  return FreelanceRepository(apiClient: ref.watch(apiClientProvider));
});
