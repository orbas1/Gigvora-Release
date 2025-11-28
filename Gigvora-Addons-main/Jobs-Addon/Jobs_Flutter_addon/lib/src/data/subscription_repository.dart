import '../api/api_client.dart';
import '../models/models.dart';

class SubscriptionRepository {
  const SubscriptionRepository(this._apiClient);

  final JobsApiClient _apiClient;

  Future<List<SubscriptionPlan>> fetchPlans() async {
    final payload = await _apiClient.getJson('/api/subscriptions/plans');
    final list = payload['data'] as List<dynamic>? ?? [];
    return list
        .map((plan) => SubscriptionPlan.fromJson(plan as Map<String, dynamic>))
        .toList();
  }

  Future<SubscriptionStatus> subscribe({required int planId}) async {
    final payload = await _apiClient.postJson(
      '/api/subscriptions',
      body: {'plan_id': planId},
    );
    return SubscriptionStatus.fromJson(payload['data'] as Map<String, dynamic>);
  }
}
