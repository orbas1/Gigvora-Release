import '../models/job.dart';
import '../models/ats.dart';
import '../models/candidate.dart';
import 'api_client.dart';

class EmployerService {
  EmployerService(this.client);

  final JobsApiClient client;

  Future<Map<String, int>> fetchKpis() async {
    final payload = await client.getJson('/api/employer/dashboard');
    return (payload['data'] as Map<String, dynamic>).map(
      (key, value) => MapEntry(key, (value as num).toInt()),
    );
  }

  Future<List<Job>> fetchJobs({String? status}) async {
    final payload = await client.getJson('/api/employer/jobs', query: {
      if (status != null) 'status': status,
    });
    return (payload['data'] as List<dynamic>? ?? [])
        .map((e) => Job.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> saveJob(Job job) async {
    await client.postJson('/api/employer/jobs/${job.id ?? ''}', body: job.toJson());
  }

  Future<Map<String, List<CandidateProfile>>> fetchPipeline(int jobId) async {
    final payload = await client.getJson('/api/employer/jobs/$jobId/pipeline');
    final stages = <String, List<CandidateProfile>>{};
    (payload['data'] as Map<String, dynamic>?)?.forEach((key, value) {
      stages[key] = (value as List<dynamic>)
          .map((e) => CandidateProfile.fromJson(e as Map<String, dynamic>))
          .toList();
    });
    return stages;
  }

  Future<void> updateStage(int candidateId, AtsStage stage) async {
    await client.putJson('/api/employer/candidates/$candidateId/stage', body: stage.toJson());
  }
}
