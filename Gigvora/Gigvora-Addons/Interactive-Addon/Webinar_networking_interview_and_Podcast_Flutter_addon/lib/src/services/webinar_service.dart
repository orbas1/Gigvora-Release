import '../api/wnip_api_client.dart';
import '../models/pagination.dart';
import '../models/recording.dart';
import '../models/webinar.dart';
import '../models/webinar_registration.dart';

class WebinarService {
  final WnipApiClient apiClient;

  WebinarService(this.apiClient);

  Future<PaginatedResponse<Webinar>> fetchUpcoming({int page = 1}) {
    return apiClient.fetchWebinars(upcoming: true, page: page);
  }

  Future<PaginatedResponse<Webinar>> fetchAll({int page = 1}) {
    return apiClient.fetchWebinars(page: page);
  }

  Future<Webinar> fetchDetail(int id) {
    return apiClient.fetchWebinarDetails(id);
  }

  Future<WebinarRegistration> register(int id, {int? ticketId}) {
    return apiClient.registerForWebinar(id, ticketId: ticketId);
  }

  Future<List<Recording>> fetchRecordings(int webinarId) async {
    final webinar = await fetchDetail(webinarId);
    return webinar.recordings;
  }
}
