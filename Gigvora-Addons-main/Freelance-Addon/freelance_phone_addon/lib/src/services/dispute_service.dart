import '../models/dispute.dart';
import 'api_client.dart';

class DisputeService {
  DisputeService(this.client);
  final ApiClient client;

  Future<List<Dispute>> listDisputes() async {
    final data = await client.get('/freelance/disputes');
    return (data as List<dynamic>).map((e) => Dispute.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Dispute> fetchDispute(int id) async {
    final data = await client.get('/freelance/disputes/$id');
    return Dispute.fromJson(data as Map<String, dynamic>);
  }

  Future<void> postMessage(int id, String body) {
    return client.post('/freelance/disputes/$id/messages', {'body': body});
  }
}
