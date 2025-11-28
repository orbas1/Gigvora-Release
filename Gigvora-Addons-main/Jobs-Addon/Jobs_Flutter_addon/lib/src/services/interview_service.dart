import '../models/interview.dart';
import 'api_client.dart';

class InterviewService {
  InterviewService(this.client);

  final JobsApiClient client;

  Future<List<InterviewInvite>> fetchInterviews() async {
    final payload = await client.getJson('/api/interviews');
    return (payload['data'] as List<dynamic>? ?? [])
        .map((e) => InterviewInvite.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> scheduleInterview(InterviewInvite invite) async {
    await client.postJson('/api/interviews', body: invite.toJson());
  }

  Future<void> reschedule(int id, String dateTime) async {
    await client.putJson('/api/interviews/$id', body: {'datetime': dateTime});
  }

  Future<void> cancel(int id) async {
    await client.delete('/api/interviews/$id');
  }
}
