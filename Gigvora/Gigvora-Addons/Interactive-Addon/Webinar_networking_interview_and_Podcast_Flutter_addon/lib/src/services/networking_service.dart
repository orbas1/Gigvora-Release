import '../api/wnip_api_client.dart';
import '../models/networking_participant.dart';
import '../models/networking_session.dart';
import '../models/pagination.dart';

class NetworkingService {
  final WnipApiClient apiClient;

  NetworkingService(this.apiClient);

  Future<PaginatedResponse<NetworkingSession>> fetchSessions({int page = 1}) {
    return apiClient.fetchNetworkingSessions(page: page);
  }

  Future<NetworkingSession> fetchDetail(int id) {
    return apiClient.fetchNetworkingSession(id);
  }

  Future<NetworkingParticipant> register(int id) {
    return apiClient.registerForNetworking(id);
  }
}
