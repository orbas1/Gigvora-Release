import '../models/models.dart';
import 'api_client.dart';

class NetworkApi extends BaseApiService {
  NetworkApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<PaginatedConnections> listConnections({int page = 1, Map<String, dynamic>? filters}) async {
    final query = <String, dynamic>{'page': page.toString(), ...?filters};
    final data = await getWithQuery('/api/pro-network/connections', query);
    return PaginatedConnections.fromJson(data as Map<String, dynamic>);
  }

  Future<NetworkSummary> mutualConnections(int userId) async {
    final data = await get('/api/pro-network/connections/mutual/$userId');
    return NetworkSummary.fromJson(data as Map<String, dynamic>);
  }
}

class RecommendationsApi extends BaseApiService {
  RecommendationsApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<RecommendationResult> _fetch(String path) async {
    final data = await get(path);
    return RecommendationResult.fromJson(data as Map<String, dynamic>);
  }

  Future<RecommendationResult> recommendedPeople() =>
      _fetch('/api/pro-network/recommendations/people');

  Future<RecommendationResult> recommendedCompanies() =>
      _fetch('/api/pro-network/recommendations/companies');

  Future<RecommendationResult> recommendedGroups() =>
      _fetch('/api/pro-network/recommendations/groups');

  Future<RecommendationResult> recommendedContent() =>
      _fetch('/api/pro-network/recommendations/content');

  Future<void> respondToRecommendation({
    required String type,
    required int id,
    required String action,
  }) async {
    await post('/api/pro-network/recommendations/$type/$id', data: {'action': action});
  }
}

class ProfileApi extends BaseApiService {
  ProfileApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<ProfessionalProfile> fetchProfile({int? userId}) async {
    final path = userId == null
        ? '/api/pro-network/profile/professional'
        : '/api/pro-network/profile/professional?user=$userId';
    final data = await get(path) as Map<String, dynamic>;
    return ProfessionalProfile.fromJson((data['profile'] as Map<String, dynamic>?) ?? data);
  }

  Future<ProfessionalProfile> updateProfile(Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/profile/professional', data: payload) as Map<String, dynamic>;
    return ProfessionalProfile.fromJson((data['profile'] as Map<String, dynamic>?) ?? data);
  }
}

class CompanyApi extends BaseApiService {
  CompanyApi({required super.baseUrl, super.tokenProvider, super.client});

  Future<CompanyProfile> fetchCompany(int companyId) async {
    final data = await get('/api/pro-network/company/$companyId') as Map<String, dynamic>;
    return CompanyProfile.fromJson((data['profile'] as Map<String, dynamic>?) ?? data,
        employeeCount: data['employee_count'] as int?);
  }

  Future<CompanyProfile> updateCompany(int companyId, Map<String, dynamic> payload) async {
    final data = await post('/api/pro-network/company/$companyId', data: payload) as Map<String, dynamic>;
    return CompanyProfile.fromJson((data['profile'] as Map<String, dynamic>?) ?? data,
        employeeCount: data['employee_count'] as int?);
  }
}
