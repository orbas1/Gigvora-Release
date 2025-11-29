import '../models/cv.dart';
import 'api_client.dart';

class CvService {
  CvService(this.client);

  final JobsApiClient client;

  Future<List<CvDocument>> fetchCvs() async {
    final payload = await client.getJson('/api/cvs');
    final data = payload['data'];
    final list =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return list
        .map((e) => CvDocument.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<CvDocument> fetchCv(int id) async {
    final payload = await client.getJson('/api/cvs/$id');
    return CvDocument.fromJson((payload['data'] ?? payload) as Map<String, dynamic>);
  }

  Future<void> saveCv(CvDocument cv) async {
    await client.postJson('/api/cvs', body: cv.toJson());
  }

  Future<void> deleteCv(int id) async {
    await client.delete('/api/cvs/$id');
  }
}
