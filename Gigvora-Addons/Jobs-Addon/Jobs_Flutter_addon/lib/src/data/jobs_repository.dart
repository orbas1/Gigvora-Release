import '../api/api_client.dart';
import '../models/models.dart';

class JobsRepository {
  const JobsRepository(this._apiClient);

  final JobsApiClient _apiClient;

  Future<List<Job>> fetchJobs({String? query}) async {
    final payload = await _apiClient.getJson('/api/jobs', query: {
      if (query != null) 'search': query,
      'per_page': '50',
    });
    final data = payload['data'];
    final list =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return list.map((job) => Job.fromJson(job as Map<String, dynamic>)).toList();
  }

  Future<Job> fetchJob(int id) async {
    final payload = await _apiClient.getJson('/api/jobs/$id');
    final data = payload['data'] ?? payload;
    return Job.fromJson(data as Map<String, dynamic>);
  }

  Future<JobApplication> apply({
    required int jobId,
    required CandidateProfile candidate,
    List<ScreeningAnswer> answers = const <ScreeningAnswer>[],
    CvDocument? cv,
    CoverLetter? coverLetter,
  }) async {
    final payload = await _apiClient.postJson('/api/applications', body: {
      'job_id': jobId,
      'candidate_id': candidate.id,
      'cover_letter_id': coverLetter?.id,
      'cv_template_id': cv?.id,
      'resume_path': cv?.url,
      'notes': candidate.summary,
      'answers': answers.map((answer) => answer.toJson()).toList(),
    });
    final data = payload['data'] ?? payload;
    return JobApplication.fromJson(data as Map<String, dynamic>);
  }

  Future<List<JobApplication>> fetchApplications({int? companyId}) async {
    final payload = await _apiClient.getJson('/api/applications', query: {
      if (companyId != null) 'company_id': '$companyId',
      'per_page': '50',
    });
    final data = payload['data'];
    final list =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return list
        .map((app) => JobApplication.fromJson(app as Map<String, dynamic>))
        .toList();
  }

  Future<JobApplication> moveToStage({
    required int applicationId,
    required int stageId,
  }) async {
    final payload = await _apiClient.postJson(
      '/api/applications/$applicationId/ats/move',
      body: {'stage_id': stageId},
    );
    final data = payload['data'] ?? payload;
    return JobApplication.fromJson(data as Map<String, dynamic>);
  }

  Future<AtsPipeline> fetchPipeline(int jobId) async {
    final payload = await _apiClient.getJson('/api/jobs/$jobId/pipeline');
    return AtsPipeline.fromJson(payload as Map<String, dynamic>);
  }

  Future<InterviewSchedule> scheduleInterview({
    required int applicationId,
    required DateTime start,
    required DateTime end,
    String? location,
    String? link,
  }) async {
    final payload = await _apiClient.postJson(
      '/api/applications/$applicationId/interviews',
      body: {
        'scheduled_at': start.toIso8601String(),
        'location': location,
        'meeting_link': link,
      },
    );
    return InterviewSchedule.fromJson(payload['data'] as Map<String, dynamic>);
  }
}
