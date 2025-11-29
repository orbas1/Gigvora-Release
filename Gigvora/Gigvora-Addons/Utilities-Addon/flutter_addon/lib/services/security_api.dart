import '../models/models.dart';
import 'api_client.dart';

class SecurityApi extends BaseApiService {
  SecurityApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<List<SecurityEvent>> events(Map<String, dynamic> filters) async {
    final data = await post('/api/pro-network/security/events', data: filters) as Map<String, dynamic>;
    final list = data['data'] as List? ?? [];
    return list.map((e) => SecurityEvent.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<List<Map<String, dynamic>>> moderationQueue(Map<String, dynamic> filters) async {
    final data = await post('/api/pro-network/moderation/queue', data: filters) as Map<String, dynamic>;
    return List<Map<String, dynamic>>.from(data['data'] as List? ?? []);
  }

  Future<Map<String, dynamic>> moderate(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/moderation/action', data: payload);
    return data as Map<String, dynamic>;
  }
}

class NewsletterApi extends BaseApiService {
  NewsletterApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<NewsletterSubscription> subscribe(String email) async {
    final data = await post('/api/pro-network/newsletters/subscribe', data: {'email': email}) as Map<String, dynamic>;
    return NewsletterSubscription.fromJson((data['subscription'] as Map<String, dynamic>?) ?? data);
  }

  Future<NewsletterSubscription> unsubscribe(String email) async {
    final data = await post('/api/pro-network/newsletters/unsubscribe', data: {'email': email}) as Map<String, dynamic>;
    return NewsletterSubscription.fromJson((data['subscription'] as Map<String, dynamic>?) ?? data);
  }
}

class AgeVerificationApi extends BaseApiService {
  AgeVerificationApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<AgeVerificationStatus> status() async {
    final data = await get('/api/pro-network/age-verification/status');
    return AgeVerificationStatus.fromJson(data as Map<String, dynamic>);
  }

  Future<AgeVerificationStatus> start(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/age-verification/start', data: payload);
    return AgeVerificationStatus.fromJson(data as Map<String, dynamic>);
  }
}
