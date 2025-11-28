import '../api/api_client.dart';
import '../models/models.dart';

class ProfileRepository {
  const ProfileRepository(this._apiClient);

  final JobsApiClient _apiClient;

  Future<Company> fetchCompany(int id) async {
    final payload = await _apiClient.getJson('/api/companies/$id');
    return Company.fromJson(payload['data'] as Map<String, dynamic>);
  }

  Future<CandidateProfile> fetchCandidate(int id) async {
    final payload = await _apiClient.getJson('/api/candidates/$id');
    return CandidateProfile.fromJson(payload['data'] as Map<String, dynamic>);
  }

  Future<CvDocument> saveCv({required int candidateId, required CvDocument cv}) async {
    final path = cv.id == null ? '/api/cvs' : '/api/cvs/${cv.id}';
    final payload = cv.id == null
        ? await _apiClient.postJson(path, body: cv.toJson())
        : await _apiClient.putJson(path, body: cv.toJson());
    return CvDocument.fromJson((payload['data'] ?? payload) as Map<String, dynamic>);
  }

  Future<CoverLetter> saveCoverLetter({
    required int candidateId,
    required CoverLetter coverLetter,
  }) async {
    final path = coverLetter.id == null
        ? '/api/cover-letters'
        : '/api/cover-letters/${coverLetter.id}';
    final payload = coverLetter.id == null
        ? await _apiClient.postJson(path, body: coverLetter.toJson())
        : await _apiClient.putJson(path, body: coverLetter.toJson());
    return CoverLetter.fromJson((payload['data'] ?? payload) as Map<String, dynamic>);
  }
}
