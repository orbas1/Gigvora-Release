import '../models/cover_letter.dart';
import 'api_client.dart';

class CoverLetterService {
  CoverLetterService(this.client);

  final JobsApiClient client;

  Future<List<CoverLetter>> fetchLetters() async {
    final payload = await client.getJson('/api/cover-letters');
    final data = payload['data'];
    final list =
        (data is Map<String, dynamic> ? data['data'] : data) as List<dynamic>? ?? [];
    return list
        .map((e) => CoverLetter.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> saveLetter(CoverLetter letter) async {
    if (letter.id == null) {
      await client.postJson('/api/cover-letters', body: letter.toJson());
    } else {
      await client.putJson('/api/cover-letters/${letter.id}', body: letter.toJson());
    }
  }
}
