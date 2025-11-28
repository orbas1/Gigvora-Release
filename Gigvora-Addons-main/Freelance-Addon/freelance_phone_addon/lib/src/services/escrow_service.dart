import '../models/escrow.dart';
import 'api_client.dart';

class EscrowService {
  EscrowService(this.client);
  final ApiClient client;

  Future<List<Escrow>> fetchEscrows() async {
    final data = await client.get('/freelance/escrow');
    return (data as List<dynamic>).map((e) => Escrow.fromJson(e as Map<String, dynamic>)).toList();
  }
}
