import 'dart:async';
import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/bid.dart';
import '../models/certification.dart';
import '../models/dispute.dart';
import '../models/dispute_stage.dart';
import '../models/education_entry.dart';
import '../models/escrow.dart';
import '../models/escrow_action.dart';
import '../models/gig.dart';
import '../models/gig_management.dart';
import '../models/pagination.dart';
import '../models/profile_portfolio.dart';
import '../models/profile_review.dart';
import '../models/project.dart';
import '../models/project_board.dart';
import '../models/recommendations.dart';
import '../models/search_result.dart';
import '../models/tag.dart';

class FreelanceApiClient {
  FreelanceApiClient({
    required this.baseUrl,
    required this.httpClient,
    required this.tokenProvider,
    this.requestTimeout = const Duration(seconds: 20),
  });

  final String baseUrl;
  final http.Client httpClient;
  final String Function() tokenProvider;
  final Duration requestTimeout;

  Uri _uri(String path, [Map<String, dynamic>? query]) {
    final base = Uri.parse(baseUrl);
    final normalizedPath = base.path.endsWith('/') ? base.path : '${base.path}/';
    return base.replace(path: '$normalizedPath$path', queryParameters: query);
  }

  Map<String, String> _headers() {
    final token = tokenProvider();
    final headers = {'Content-Type': 'application/json'};
    if (token.isNotEmpty) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  Future<Map<String, dynamic>> _get(String path, [Map<String, dynamic>? query]) async {
    final response = await _send(() => httpClient.get(_uri(path, query), headers: _headers()));
    return _decode(response);
  }

  Future<Map<String, dynamic>> _post(String path, Map<String, dynamic> body) async {
    final response = await _send(
      () => httpClient.post(_uri(path), headers: _headers(), body: jsonEncode(body)),
    );
    return _decode(response);
  }

  Future<Map<String, dynamic>> _put(String path, Map<String, dynamic> body) async {
    final response = await _send(
      () => httpClient.put(_uri(path), headers: _headers(), body: jsonEncode(body)),
    );
    return _decode(response);
  }

  Future<void> _delete(String path) async {
    final response = await _send(() => httpClient.delete(_uri(path), headers: _headers()));
    _decode(response);
  }

  Future<http.Response> _send(Future<http.Response> Function() request) async {
    try {
      return await request().timeout(requestTimeout);
    } on TimeoutException catch (error) {
      throw FreelanceApiException('Request timed out after ${requestTimeout.inSeconds}s: ${error.message ?? ''}');
    }
  }

  Map<String, dynamic> _decode(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      if (response.body.isEmpty) {
        return <String, dynamic>{};
      }
      try {
        return jsonDecode(response.body) as Map<String, dynamic>;
      } on FormatException catch (error) {
        throw FreelanceApiException('Unable to parse response: ${error.message}');
      }
    }
    throw FreelanceApiException('Request failed [${response.statusCode}]: ${response.body}');
  }

  Future<PagedResult<Gig>> fetchGigs({required Map<String, dynamic> params}) async {
    final data = await _get('gigs', params);
    final list = (data['data']?['list'] as List?)
            ?.map((json) => Gig.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <Gig>[];
    final pagination = Pagination.fromJson(data['data']?['pagination'] as Map<String, dynamic>?);
    return PagedResult(items: list, pagination: pagination);
  }

  Future<Gig> fetchGigDetails(int id) async {
    final data = await _get('gig/$id');
    return Gig.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<List<FreelanceTag>> fetchTags({String? type}) async {
    final data = await _get('freelance/tags', {if (type != null) 'type': type});
    return (data['data']?['tags'] as List?)
            ?.map((json) => FreelanceTag.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <FreelanceTag>[];
  }

  Future<PagedResult<Project>> fetchProjects({required Map<String, dynamic> params}) async {
    final data = await _get('projects', params);
    final list = (data['data']?['projects'] as List?)
            ?.map((json) => Project.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <Project>[];
    final pagination = Pagination.fromJson(data['data']?['pagination'] as Map<String, dynamic>?);
    return PagedResult(items: list, pagination: pagination);
  }

  Future<Project> fetchProjectDetails(String slug) async {
    final data = await _get('project/$slug');
    return Project.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<List<Dispute>> fetchDisputes({required int perPage}) async {
    final data = await _get('disputes', {'per_page': perPage});
    return (data['data']?['list'] as List?)
            ?.map((json) => Dispute.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <Dispute>[];
  }

  Future<List<Escrow>> fetchEscrows() async {
    final data = await _get('escrows');
    return (data['data']?['list'] as List?)
            ?.map((json) => Escrow.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <Escrow>[];
  }

  Future<ProjectBoard> fetchProjectBoard(String slug) async {
    final data = await _get('project/$slug/board');
    return ProjectBoard.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<FreelanceSearchResult> searchFreelance({
    required String query,
    int page = 1,
    int perPage = 20,
  }) async {
    final data = await _get('freelance/search', {
      'q': query,
      'page': page.toString(),
      'per_page': perPage.toString(),
    });

    return FreelanceSearchResult.fromJson((data['data'] as Map<String, dynamic>?) ?? data);
  }

  Future<FreelanceRecommendations> fetchRecommendations({int limit = 10}) async {
    final data = await _get('freelance/recommendations', {'limit': limit.toString()});
    return FreelanceRecommendations.fromJson((data['data'] as Map<String, dynamic>?) ?? data);
  }

  Future<void> updateProfileTags({required List<String> tags, String type = 'freelancer'}) async {
    await _post('freelance/profile/tags', {
      'tags': tags,
      'type': type,
    });
  }

  Future<void> updateGigTags({required int gigId, required List<String> tags}) async {
    await _post('gig/$gigId/tags', {
      'tags': tags,
    });
  }

  Future<List<ProfilePortfolio>> fetchPortfolios({int? userId}) async {
    final data = await _get('profile/portfolios', {if (userId != null) 'user_id': userId.toString()});
    return (data['data']?['portfolios'] as List?)
            ?.map((json) => ProfilePortfolio.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <ProfilePortfolio>[];
  }

  Future<ProfilePortfolio> addPortfolio(Map<String, dynamic> payload) async {
    final data = await _post('profile/portfolio', payload);
    return ProfilePortfolio.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<ProfilePortfolio> updatePortfolio(int id, Map<String, dynamic> payload) async {
    final data = await _put('profile/portfolio/$id', payload);
    return ProfilePortfolio.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<void> deletePortfolio(int id) async {
    await _delete('profile/portfolio/$id');
  }

  Future<List<EducationEntry>> fetchEducations({int? userId}) async {
    final data = await _get('profile/educations', {if (userId != null) 'user_id': userId.toString()});
    return (data['data']?['educations'] as List?)
            ?.map((json) => EducationEntry.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <EducationEntry>[];
  }

  Future<EducationEntry> addEducation(Map<String, dynamic> payload) async {
    final data = await _post('profile/education', payload);
    return EducationEntry.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<EducationEntry> updateEducation(int id, Map<String, dynamic> payload) async {
    final data = await _put('profile/education/$id', payload);
    return EducationEntry.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<void> deleteEducation(int id) async {
    await _delete('profile/education/$id');
  }

  Future<List<Certification>> fetchCertifications({int? userId}) async {
    final data = await _get('profile/certifications', {if (userId != null) 'user_id': userId.toString()});
    return (data['data']?['certifications'] as List?)
            ?.map((json) => Certification.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <Certification>[];
  }

  Future<Certification> addCertification(Map<String, dynamic> payload) async {
    final data = await _post('profile/certification', payload);
    return Certification.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<Certification> updateCertification(int id, Map<String, dynamic> payload) async {
    final data = await _put('profile/certification/$id', payload);
    return Certification.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<void> deleteCertification(int id) async {
    await _delete('profile/certification/$id');
  }

  Future<Map<String, dynamic>> fetchProfileReviews(int userId) async {
    final data = await _get('profile/reviews', {'user_id': userId.toString()});
    final reviews = (data['data']?['reviews'] as List?)
            ?.map((json) => ProfileReview.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <ProfileReview>[];
    final average = data['data']?['average_rating'] is num
        ? (data['data']['average_rating'] as num).toDouble()
        : double.tryParse(data['data']?['average_rating']?.toString() ?? '');
    return {'reviews': reviews, 'average_rating': average};
  }

  Future<ProfileReview> submitProfileReview({
    required int userId,
    required double rating,
    String? comment,
    String? reference,
  }) async {
    final data = await _post('profile/review', {
      'user_id': userId,
      'rating': rating,
      if (comment != null) 'comment': comment,
      if (reference != null) 'reference': reference,
    });
    return ProfileReview.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<GigManagement> fetchGigManagement(int id) async {
    final data = await _get('gig/$id/management');
    return GigManagement.fromJson(data['data'] as Map<String, dynamic>?);
  }

  Future<List<DisputeStage>> fetchDisputeStages(int disputeId) async {
    final data = await _get('dispute/$disputeId/stages');
    return (data['data']?['stages'] as List?)
            ?.map((json) => DisputeStage.fromJson(json as Map<String, dynamic>?))
            .toList() ??
        const <DisputeStage>[];
  }

  Future<void> advanceDispute({required int disputeId, required String stage, String? notes, String? decision}) async {
    await _post('dispute/$disputeId/advance', {
      'stage': stage,
      if (notes != null) 'notes': notes,
      if (decision != null) 'decision': decision,
    });
  }

  Future<List<EscrowAction>> fetchEscrowActions() async {
    final data = await _get('escrows/manage');
    final actions = (data['data'] as List?)
            ?.expand((item) => (item['actions'] as List? ?? const []).map((action) => EscrowAction.fromJson(action as Map<String, dynamic>?)))
            .toList() ??
        const <EscrowAction>[];
    return actions;
  }

  Future<void> partialRelease({required int escrowId, required double amount, required String releasedBy, String? notes}) async {
    await _post('escrow/$escrowId/partial-release', {
      'amount': amount,
      'released_by': releasedBy,
      if (notes != null) 'notes': notes,
    });
  }

  Future<void> recordEscrowDecision({required int escrowId, required String decision, required String admin, String? notes}) async {
    await _post('escrow/$escrowId/decision', {
      'decision': decision,
      'admin': admin,
      if (notes != null) 'notes': notes,
    });
  }

  Future<Dispute> openDispute({
    required String subject,
    required String referenceType,
    required int referenceId,
    String? message,
  }) async {
    final response = await _post('dispute', {
      'subject': subject,
      'reference_type': referenceType,
      'reference_id': referenceId,
      if (message != null) 'message': message,
    });
    return Dispute.fromJson(response['data'] as Map<String, dynamic>?);
  }

  Future<Bid> placeBid({
    required String projectSlug,
    required double amount,
    required String currency,
    String? coverLetter,
  }) async {
    final response = await _post('project/$projectSlug/bid', {
      'amount': amount,
      'currency': currency,
      if (coverLetter != null) 'cover_letter': coverLetter,
    });
    return Bid.fromJson(response['data'] as Map<String, dynamic>?);
  }

  Future<void> toggleFavourite({required int id, required String type}) async {
    await _post('favourites', {
      'corresponding_id': id,
      'type': type,
    });
  }
}

class FreelanceApiException implements Exception {
  FreelanceApiException(this.message);
  final String message;

  @override
  String toString() => message;
}
