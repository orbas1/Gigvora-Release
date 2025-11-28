import 'dart:convert';
import 'dart:async';

import 'package:http/http.dart' as http;

import '../models/interview.dart';
import '../models/interview_score.dart';
import '../models/interview_slot.dart';
import '../models/networking_participant.dart';
import '../models/networking_session.dart';
import '../models/pagination.dart';
import '../models/podcast_episode.dart';
import '../models/podcast_series.dart';
import '../models/webinar.dart';
import '../models/webinar_registration.dart';
import 'api_exception.dart';

class WnipApiClient {
  final String baseUrl;
  final http.Client _httpClient;
  final FutureOr<String?> Function()? tokenProvider;

  WnipApiClient({
    required this.baseUrl,
    http.Client? httpClient,
    this.tokenProvider,
  }) : _httpClient = httpClient ?? http.Client();

  Uri _buildUri(String path, [Map<String, String>? queryParameters]) {
    final normalized = path.startsWith('http')
        ? Uri.parse(path)
        : Uri.parse(baseUrl).resolve(path);
    return normalized.replace(queryParameters: queryParameters);
  }

  Future<Map<String, String>> _headers() async {
    final headers = <String, String>{'Content-Type': 'application/json', 'Accept': 'application/json'};
    final token = await tokenProvider?.call();
    if (token != null && token.isNotEmpty) {
      headers['Authorization'] = 'Bearer ' + token;
    }
    return headers;
  }

  Future<dynamic> _handleResponse(http.Response response) {
    final body = response.body.isNotEmpty ? jsonDecode(response.body) : null;
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return body;
    }
    throw ApiException(
      'Request failed with status ' + response.statusCode.toString(),
      statusCode: response.statusCode,
      details: body is Map<String, dynamic> ? body : null,
    );
  }

  Future<PaginatedResponse<Webinar>> fetchWebinars({bool upcoming = false, int page = 1}) async {
    final uri = _buildUri('/wnip/webinars', {
      if (upcoming) 'upcoming': '1',
      'page': page.toString(),
    });
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PaginatedResponse.fromJson(body, (json) => Webinar.fromJson(Map<String, dynamic>.from(json as Map)));
  }

  Future<Webinar> fetchWebinarDetails(int id) async {
    final uri = _buildUri('/wnip/webinars/' + id.toString());
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Webinar.fromJson(body);
  }

  Future<Webinar> createWebinar(WebinarPayload payload) async {
    final uri = _buildUri('/wnip/webinars');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Webinar.fromJson(body);
  }

  Future<Webinar> updateWebinar(int id, WebinarUpdatePayload payload) async {
    final uri = _buildUri('/wnip/webinars/' + id.toString());
    final response = await _httpClient.put(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Webinar.fromJson(body);
  }

  Future<void> deleteWebinar(int id) async {
    final uri = _buildUri('/wnip/webinars/' + id.toString());
    final response = await _httpClient.delete(uri, headers: await _headers());
    await _handleResponse(response);
  }

  Future<WebinarRegistration> registerForWebinar(int id, {int? ticketId}) async {
    final uri = _buildUri('/wnip/webinars/' + id.toString() + '/register');
    final response = await _httpClient.post(
      uri,
      headers: await _headers(),
      body: jsonEncode({'ticket_id': ticketId}),
    );
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return WebinarRegistration.fromJson(body);
  }

  Future<Webinar> toggleWebinarLive(int id) async {
    final uri = _buildUri('/wnip/webinars/' + id.toString() + '/toggle-live');
    final response = await _httpClient.post(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Webinar.fromJson(body);
  }

  Future<PaginatedResponse<NetworkingSession>> fetchNetworkingSessions({int page = 1}) async {
    final uri = _buildUri('/wnip/networking', {'page': page.toString()});
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PaginatedResponse.fromJson(
      body,
      (json) => NetworkingSession.fromJson(Map<String, dynamic>.from(json as Map)),
    );
  }

  Future<NetworkingSession> fetchNetworkingSession(int id) async {
    final uri = _buildUri('/wnip/networking/' + id.toString());
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return NetworkingSession.fromJson(body);
  }

  Future<NetworkingSession> createNetworkingSession(NetworkingSessionPayload payload) async {
    final uri = _buildUri('/wnip/networking');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return NetworkingSession.fromJson(body);
  }

  Future<NetworkingSession> updateNetworkingSession(int id, NetworkingSessionUpdatePayload payload) async {
    final uri = _buildUri('/wnip/networking/' + id.toString());
    final response = await _httpClient.put(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return NetworkingSession.fromJson(body);
  }

  Future<NetworkingParticipant> registerForNetworking(int id) async {
    final uri = _buildUri('/wnip/networking/' + id.toString() + '/register');
    final response = await _httpClient.post(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return NetworkingParticipant.fromJson(body);
  }

  Future<void> rotateNetworking(int id) async {
    final uri = _buildUri('/wnip/networking/' + id.toString() + '/rotate');
    final response = await _httpClient.post(uri, headers: await _headers());
    await _handleResponse(response);
  }

  Future<PaginatedResponse<PodcastSeries>> fetchPodcastSeries({int page = 1}) async {
    final uri = _buildUri('/wnip/podcasts', {'page': page.toString()});
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PaginatedResponse.fromJson(
      body,
      (json) => PodcastSeries.fromJson(Map<String, dynamic>.from(json as Map)),
    );
  }

  Future<PodcastSeries> fetchPodcastSeriesDetails(int id) async {
    final uri = _buildUri('/wnip/podcast-series/' + id.toString());
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PodcastSeries.fromJson(body);
  }

  Future<PodcastSeries> createPodcastSeries(PodcastSeriesPayload payload) async {
    final uri = _buildUri('/wnip/podcast-series');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PodcastSeries.fromJson(body);
  }

  Future<PodcastSeries> updatePodcastSeries(int id, PodcastSeriesUpdatePayload payload) async {
    final uri = _buildUri('/wnip/podcast-series/' + id.toString());
    final response = await _httpClient.put(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PodcastSeries.fromJson(body);
  }

  Future<PodcastEpisode> createPodcastEpisode(int seriesId, PodcastEpisodePayload payload) async {
    final uri = _buildUri('/wnip/podcast-series/' + seriesId.toString() + '/episodes');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PodcastEpisode.fromJson(body);
  }

  Future<PodcastEpisode> publishPodcastEpisode(int seriesId, int episodeId) async {
    final uri = _buildUri(
      '/wnip/podcast-series/' + seriesId.toString() + '/episodes/' + episodeId.toString() + '/publish',
    );
    final response = await _httpClient.post(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PodcastEpisode.fromJson(body);
  }

  Future<PaginatedResponse<Interview>> fetchInterviews({int page = 1}) async {
    final uri = _buildUri('/wnip/interviews', {'page': page.toString()});
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return PaginatedResponse.fromJson(
      body,
      (json) => Interview.fromJson(Map<String, dynamic>.from(json as Map)),
    );
  }

  Future<Interview> fetchInterviewDetails(int id) async {
    final uri = _buildUri('/wnip/interviews/' + id.toString());
    final response = await _httpClient.get(uri, headers: await _headers());
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Interview.fromJson(body);
  }

  Future<Interview> createInterview(InterviewPayload payload) async {
    final uri = _buildUri('/wnip/interviews');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Interview.fromJson(body);
  }

  Future<Interview> updateInterview(int id, InterviewUpdatePayload payload) async {
    final uri = _buildUri('/wnip/interviews/' + id.toString());
    final response = await _httpClient.put(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return Interview.fromJson(body);
  }

  Future<InterviewSlot> addInterviewSlot(int interviewId, InterviewSlotPayload payload) async {
    final uri = _buildUri('/wnip/interviews/' + interviewId.toString() + '/slots');
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return InterviewSlot.fromJson(body);
  }

  Future<InterviewScore> submitInterviewScore(
    int interviewId,
    int slotId,
    InterviewScorePayload payload,
  ) async {
    final uri = _buildUri(
      '/wnip/interviews/' + interviewId.toString() + '/slots/' + slotId.toString() + '/score',
    );
    final response = await _httpClient.post(uri, headers: await _headers(), body: jsonEncode(payload.toJson()));
    final body = await _handleResponse(response) as Map<String, dynamic>;
    return InterviewScore.fromJson(body);
  }
}
