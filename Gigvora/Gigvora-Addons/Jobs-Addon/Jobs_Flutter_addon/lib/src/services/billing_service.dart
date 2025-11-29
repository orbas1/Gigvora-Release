import '../models/billing.dart';
import 'api_client.dart';

class BillingService {
  BillingService(this.client);

  final JobsApiClient client;

  Future<BillingPlan> fetchCurrentPlan() async {
    final payload = await client.getJson('/api/billing/plan');
    return BillingPlan.fromJson(payload['data'] as Map<String, dynamic>);
  }

  Future<List<BillingPlan>> fetchPlans() async {
    final payload = await client.getJson('/api/billing/plans');
    return (payload['data'] as List<dynamic>? ?? [])
        .map((e) => BillingPlan.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<List<InvoiceSummary>> fetchInvoices() async {
    final payload = await client.getJson('/api/billing/invoices');
    return (payload['data'] as List<dynamic>? ?? [])
        .map((e) => InvoiceSummary.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> choosePlan(int planId) async {
    await client.postJson('/api/billing/plan', body: {'plan_id': planId});
  }

  Future<void> buyCredits(int quantity) async {
    await client.postJson('/api/billing/credits', body: {'quantity': quantity});
  }
}
