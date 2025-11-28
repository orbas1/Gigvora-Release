import '../models/screening.dart';
import 'api_client.dart';

class ScreeningService {
  ScreeningService(this.client);

  final JobsApiClient client;

  Future<List<ScreeningTemplate>> fetchTemplates() async {
    final payload = await client.getJson('/api/screening');
    return (payload['data'] as List<dynamic>? ?? [])
        .map((e) => ScreeningTemplate.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> saveTemplate(ScreeningTemplate template) async {
    await client.postJson('/api/screening/${template.id ?? ''}', body: template.toJson());
  }
}
