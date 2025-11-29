import 'package:collection/collection.dart';

import '../models/job.dart';
import '../models/company.dart';
import '../models/screening.dart';
import 'api_client.dart';

class JobsService {
  JobsService(this.client);

  final JobsApiClient client;

  Future<List<Job>> fetchJobs({String? keyword, String? location}) async {
    final payload = await client.getJson('/api/jobs', query: {
      if (keyword?.isNotEmpty == true) 'search': keyword!,
      if (location?.isNotEmpty == true) 'location': location!,
      'per_page': '50',
    });
    final data = payload['data'];
    final jobs =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return jobs.map((e) => Job.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Job> fetchJob(int id) async {
    final payload = await client.getJson('/api/jobs/$id');
    return Job.fromJson((payload['data'] ?? payload) as Map<String, dynamic>);
  }

  Future<List<Job>> fetchSavedJobs() async {
    final payload = await client.getJson('/api/jobs/saved');
    final data = payload['data'];
    final list =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return list
        .map((e) => Job.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> toggleSave(int jobId, bool saved) async {
    await client.postJson('/api/jobs/$jobId/save', body: {'saved': saved});
  }

  Future<List<Job>> fetchSimilar(Job job) async {
    final payload = await client.getJson('/api/jobs/${job.id}/similar');
    final data = payload['data'] as List<dynamic>? ?? [];
    return data
        .map((e) => Job.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<List<Company>> fetchRecommendedCompanies() async {
    final payload = await client.getJson('/api/jobs', query: {'featured': '1', 'per_page': '20'});
    final data = payload['data'];
    final jobs =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    final companies = jobs
        .map((e) => Company.fromJson((e as Map<String, dynamic>)['company'] as Map<String, dynamic>))
        .toList();
    final seen = <int>{};
    return companies.where((c) => seen.add(c.id)).toList();
  }

  Future<List<ScreeningQuestion>> fetchScreeningQuestions(int jobId) async {
    final payload = await client.getJson('/api/jobs/$jobId/screening');
    final questions = payload['data'] as List<dynamic>? ?? [];
    return questions
        .map((e) => ScreeningQuestion.fromJson(e as Map<String, dynamic>))
        .toList();
  }
}
