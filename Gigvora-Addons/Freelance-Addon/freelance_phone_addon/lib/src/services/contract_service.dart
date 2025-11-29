import '../models/contract.dart';
import '../models/milestone.dart';
import 'api_client.dart';

class ContractService {
  ContractService(this.client);
  final ApiClient client;

  Future<List<Contract>> listContracts(String role) async {
    final data = await client.get('/freelance/$role/contracts');
    return (data as List<dynamic>).map((e) => Contract.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Contract> fetchContract(int id) async {
    final data = await client.get('/freelance/contracts/$id');
    return Contract.fromJson(data as Map<String, dynamic>);
  }

  Future<void> updateMilestone(int contractId, Milestone milestone, String action) {
    return client.post('/freelance/contracts/$contractId/milestones', {
      'title': milestone.title,
      'action': action,
    });
  }
}
