import '../models/project.dart';
import '../models/proposal.dart';
import 'api_client.dart';

class ProjectService {
  ProjectService(this.client);
  final ApiClient client;

  Future<List<Project>> browseProjects() async {
    final data = await client.get('/freelance/projects');
    return (data as List<dynamic>).map((e) => Project.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Project> fetchProject(int id) async {
    final data = await client.get('/freelance/projects/$id');
    return Project.fromJson(data as Map<String, dynamic>);
  }

  Future<Proposal> submitProposal(int projectId, Map<String, dynamic> payload) async {
    final data = await client.post('/freelance/projects/$projectId/proposals', payload);
    return Proposal.fromJson(data as Map<String, dynamic>);
  }
}
