import '../models/gig.dart';
import 'api_client.dart';

class GigService {
  GigService(this.client);
  final ApiClient client;

  Future<List<Gig>> fetchMyGigs({String? status}) async {
    final data = await client.get('/freelance/gigs?status=${status ?? ''}');
    return (data as List<dynamic>).map((e) => Gig.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Gig> saveGig(Map<String, dynamic> payload) async {
    final data = await client.post('/freelance/gigs', payload);
    return Gig.fromJson(data as Map<String, dynamic>);
  }
}
